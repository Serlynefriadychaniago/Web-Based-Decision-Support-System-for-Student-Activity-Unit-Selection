<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PendaftaranUkmTest extends TestCase
{
    use RefreshDatabase;

    protected $userMahasiswa;
    protected $mahasiswaRecord;
    protected $ukmRecord;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Buat User Mahasiswa
        $this->userMahasiswa = User::create([
            'name'     => 'Mahasiswa Test',
            'email'    => 'mahasiswa_test@umc.ac.id',
            'password' => bcrypt('password123'),
            'role'     => 'mahasiswa',
        ]);

        // 2. Buat Record Mahasiswa (Profil Lengkap 8 Field)
        $mahasiswaId = DB::table('mahasiswa')->insertGetId([
            'user_id'       => $this->userMahasiswa->id,
            'nama'          => 'Mahasiswa Test',
            'nim'           => '220101001',
            'prodi'         => 'Teknik Informatika',
            'fakultas'      => 'Fakultas Teknik',
            'angkatan'      => '2022',
            'jenis_kelamin' => 'L',
            'no_hp'         => '081234567890',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $this->mahasiswaRecord = DB::table('mahasiswa')->where('id', $mahasiswaId)->first();

        // 3. Buat Record UKM Test
        $ukmId = DB::table('ukm')->insertGetId([
            'nama_ukm'   => 'UKM Olahraga Test',
            'deskripsi'  => 'UKM Pengembangan bakat olahraga mahasiswa UMC.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->ukmRecord = DB::table('ukm')->where('id', $ukmId)->first();
    }

    /** @test */
    public function form_pendaftaran_dapat_dibuka()
    {
        $response = $this->actingAs($this->userMahasiswa)
            ->get(route('mahasiswa.pendaftaran.create', $this->ukmRecord->id));

        $response->assertStatus(200);
        $response->assertSee('Pendaftaran ' . $this->ukmRecord->nama_ukm);
        $response->assertSee($this->mahasiswaRecord->nama);
        $response->assertSee($this->mahasiswaRecord->nim);
    }

    /** @test */
    public function seluruh_field_wajib_tervalidasi()
    {
        $response = $this->actingAs($this->userMahasiswa)
            ->post(route('mahasiswa.pendaftaran.store'), [
                'ukm_id' => $this->ukmRecord->id,
                // Kosongkan seluruh field wajib
            ]);

        $response->assertSessionHasErrors([
            'alasan_mendaftar',
            'ketertarikan',
            'pengalaman',
            'keterampilan',
            'kesediaan_mengikuti_kegiatan',
            'kontribusi',
            'kesediaan_menaati_aturan',
            'komitmen_aktif',
        ]);
    }

    /** @test */
    public function data_pendaftaran_dan_jawaban_pertanyaan_tersimpan_ke_database()
    {
        $payload = [
            'ukm_id'                       => $this->ukmRecord->id,
            'alasan_mendaftar'             => 'Ingin mengasah bakat olahraga dan menambah prestasi kampus.',
            'ketertarikan'                 => 'Sangat berminat mengembangkan skill dalam cabang futsal dan basket.',
            'pengalaman'                   => 'Cukup berpengalaman',
            'detail_pengalaman'            => 'Pernah menjadi tim futsal SMA dan menjuarai kejuaraan antar sekolah.',
            'keterampilan'                 => 'Futsal, Basket, Kapten Tim, Kepemimpinan',
            'kesediaan_mengikuti_kegiatan' => 'Sangat siap',
            'kesesuaian_jadwal'            => 'Siap menyesuaikan',
            'kontribusi'                   => 'Siap menjadi atlet utama UKM dan membantu kepengurusan acara.',
            'kesediaan_menaati_aturan'     => 'Ya',
            'komitmen_aktif'               => 'Mengembangkan prestasi',
        ];

        $response = $this->actingAs($this->userMahasiswa)
            ->post(route('mahasiswa.pendaftaran.store'), $payload);

        $response->assertRedirect(route('mahasiswa.pendaftaran.index'));
        $response->assertSessionHas('success');

        // Pengecekan data tersimpan di tabel pendaftaran_ukm
        $this->assertDatabaseHas('pendaftaran_ukm', [
            'mahasiswa_id'                 => $this->mahasiswaRecord->id,
            'ukm_id'                      => $this->ukmRecord->id,
            'alasan_mendaftar'            => $payload['alasan_mendaftar'],
            'ketertarikan'                => $payload['ketertarikan'],
            'pengalaman'                  => $payload['pengalaman'],
            'detail_pengalaman'           => $payload['detail_pengalaman'],
            'keterampilan'                => $payload['keterampilan'],
            'kesediaan_mengikuti_kegiatan' => $payload['kesediaan_mengikuti_kegiatan'],
            'kontribusi'                  => $payload['kontribusi'],
            'kesediaan_menaati_aturan'    => $payload['kesediaan_menaati_aturan'],
            'komitmen_aktif'              => $payload['komitmen_aktif'],
            'status'                      => 'menunggu_verifikasi',
        ]);

        // Pengecekan timeline log riwayat status
        $pendaftaran = DB::table('pendaftaran_ukm')
            ->where('mahasiswa_id', $this->mahasiswaRecord->id)
            ->first();

        $this->assertDatabaseHas('riwayat_status_pendaftaran', [
            'pendaftaran_id' => $pendaftaran->id,
            'status'         => 'menunggu_verifikasi',
            'diubah_oleh'    => $this->userMahasiswa->id,
        ]);
    }

    /** @test */
    public function duplicate_registration_tetap_ditolak()
    {
        // Pendaftaran Pertama
        DB::table('pendaftaran_ukm')->insert([
            'nomor_pendaftaran'            => 'REG-TEST-001',
            'mahasiswa_id'                 => $this->mahasiswaRecord->id,
            'ukm_id'                      => $this->ukmRecord->id,
            'alasan_mendaftar'            => 'Pendaftaran Pertama',
            'ketertarikan'                => 'Tinggi',
            'pengalaman'                  => 'Belum pernah',
            'keterampilan'                => 'Dasar',
            'kesediaan_mengikuti_kegiatan' => 'Siap',
            'kontribusi'                  => 'Kontribusi 1',
            'kesediaan_menaati_aturan'    => 'Ya',
            'komitmen_aktif'              => 'Menambah pengalaman',
            'status'                      => 'menunggu_verifikasi',
            'tanggal_daftar'              => now(),
        ]);

        // Coba Pendaftaran Kedua untuk UKM yang sama
        $response = $this->actingAs($this->userMahasiswa)
            ->post(route('mahasiswa.pendaftaran.store'), [
                'ukm_id'                       => $this->ukmRecord->id,
                'alasan_mendaftar'             => 'Pendaftaran Kedua',
                'ketertarikan'                 => 'Tinggi',
                'pengalaman'                   => 'Belum pernah',
                'keterampilan'                 => 'Dasar',
                'kesediaan_mengikuti_kegiatan' => 'Siap',
                'kontribusi'                   => 'Kontribusi 2',
                'kesediaan_menaati_aturan'     => 'Ya',
                'komitmen_aktif'               => 'Menambah pengalaman',
            ]);

        $response->assertRedirect(route('mahasiswa.pendaftaran.index'));
        $response->assertSessionHas('error', 'Anda sudah mendaftar di UKM ini sebelumnya.');

        // Pastikan jumlah record tetap 1
        $count = DB::table('pendaftaran_ukm')
            ->where('mahasiswa_id', $this->mahasiswaRecord->id)
            ->where('ukm_id', $this->ukmRecord->id)
            ->count();

        $this->assertEquals(1, $count);
    }

    /** @test */
    public function data_mahasiswa_lain_tidak_dapat_diakses_atau_diubah()
    {
        // 1. Buat User Mahasiswa Lain
        $otherUser = User::create([
            'name'     => 'Mahasiswa Lain',
            'email'    => 'mahasiswa_lain@umc.ac.id',
            'password' => bcrypt('password123'),
            'role'     => 'mahasiswa',
        ]);

        $otherMhsId = DB::table('mahasiswa')->insertGetId([
            'user_id'       => $otherUser->id,
            'nama'          => 'Mahasiswa Lain',
            'nim'           => '220101002',
            'prodi'         => 'Teknik Informatika',
            'fakultas'      => 'Fakultas Teknik',
            'angkatan'      => '2022',
            'jenis_kelamin' => 'P',
            'no_hp'         => '081234567899',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // 2. Buat Pendaftaran Milik User Mahasiswa Utama (status perlu_perbaikan)
        $pendaftaranId = DB::table('pendaftaran_ukm')->insertGetId([
            'nomor_pendaftaran'            => 'REG-TEST-002',
            'mahasiswa_id'                 => $this->mahasiswaRecord->id,
            'ukm_id'                      => $this->ukmRecord->id,
            'alasan_mendaftar'            => 'Pendaftaran Awal',
            'ketertarikan'                => 'Tinggi',
            'pengalaman'                  => 'Belum pernah',
            'keterampilan'                => 'Dasar',
            'kesediaan_mengikuti_kegiatan' => 'Siap',
            'kontribusi'                  => 'Kontribusi Awal',
            'kesediaan_menaati_aturan'    => 'Ya',
            'komitmen_aktif'              => 'Menambah pengalaman',
            'status'                      => 'perlu_perbaikan',
            'tanggal_daftar'              => now(),
        ]);

        // 3. User Lain mencoba mengakses halaman edit pendaftaran milik Mahasiswa Utama
        $responseGet = $this->actingAs($otherUser)
            ->get(route('mahasiswa.pendaftaran.edit', $pendaftaranId));

        $responseGet->assertRedirect(route('mahasiswa.pendaftaran.index'));
        $responseGet->assertSessionHas('error');

        // 4. User Lain mencoba meng-update pendaftaran milik Mahasiswa Utama
        $responsePut = $this->actingAs($otherUser)
            ->put(route('mahasiswa.pendaftaran.update', $pendaftaranId), [
                'alasan_mendaftar'             => 'Alasan Dibajak',
                'ketertarikan'                 => 'Tinggi',
                'pengalaman'                   => 'Belum pernah',
                'keterampilan'                 => 'Dasar',
                'kesediaan_mengikuti_kegiatan' => 'Siap',
                'kontribusi'                   => 'Kontribusi Dibajak',
                'kesediaan_menaati_aturan'     => 'Ya',
                'komitmen_aktif'               => 'Menambah pengalaman',
            ]);

        $responsePut->assertRedirect(route('mahasiswa.pendaftaran.index'));
        $responsePut->assertSessionHas('error');

        // Pastikan isi data pendaftaran di DB tidak berubah oleh User Lain
        $this->assertDatabaseHas('pendaftaran_ukm', [
            'id'               => $pendaftaranId,
            'alasan_mendaftar' => 'Pendaftaran Awal',
        ]);
    }

    /** @test */
    public function alur_perbaikan_pendaftaran_mahasiswa_berjalan()
    {
        // 1. Pendaftaran Berstatus perlu_perbaikan
        $pendaftaranId = DB::table('pendaftaran_ukm')->insertGetId([
            'nomor_pendaftaran'            => 'REG-TEST-003',
            'mahasiswa_id'                 => $this->mahasiswaRecord->id,
            'ukm_id'                      => $this->ukmRecord->id,
            'alasan_mendaftar'            => 'Alasan Kurang Jelas',
            'ketertarikan'                => 'Tinggi',
            'pengalaman'                  => 'Belum pernah',
            'keterampilan'                => 'Dasar',
            'kesediaan_mengikuti_kegiatan' => 'Siap',
            'kontribusi'                  => 'Belum detail',
            'kesediaan_menaati_aturan'    => 'Ya',
            'komitmen_aktif'              => 'Menambah pengalaman',
            'status'                      => 'perlu_perbaikan',
            'tanggal_daftar'              => now(),
        ]);

        DB::table('riwayat_status_pendaftaran')->insert([
            'pendaftaran_id' => $pendaftaranId,
            'status'         => 'perlu_perbaikan',
            'catatan'        => 'Mohon perjelas alur kontribusi Anda.',
            'diubah_oleh'    => 1,
            'created_at'     => now(),
        ]);

        // 2. Mahasiswa Membuka Halaman Edit
        $responseEdit = $this->actingAs($this->userMahasiswa)
            ->get(route('mahasiswa.pendaftaran.edit', $pendaftaranId));

        $responseEdit->assertStatus(200);
        $responseEdit->assertSee('Mohon perjelas alur kontribusi Anda.');

        // 3. Mahasiswa Mengirimkan Perbaikan
        $responseUpdate = $this->actingAs($this->userMahasiswa)
            ->put(route('mahasiswa.pendaftaran.update', $pendaftaranId), [
                'alasan_mendaftar'             => 'Alasan Sudah Diperjelas dan Lengkap',
                'ketertarikan'                 => 'Sangat Berminat',
                'pengalaman'                   => 'Pernah sedikit',
                'detail_pengalaman'            => 'Pernah mengikuti pelatihan singkat.',
                'keterampilan'                 => 'Public Speaking & Organisasi',
                'kesediaan_mengikuti_kegiatan' => 'Sangat siap',
                'kontribusi'                   => 'Siap mengabdi di pengurusan humas UKM.',
                'kesediaan_menaati_aturan'     => 'Ya',
                'komitmen_aktif'               => 'Mengembangkan prestasi',
            ]);

        $responseUpdate->assertRedirect(route('mahasiswa.pendaftaran.index'));
        $responseUpdate->assertSessionHas('success');

        // Pastikan status berubah kembali ke 'menunggu_verifikasi'
        $this->assertDatabaseHas('pendaftaran_ukm', [
            'id'               => $pendaftaranId,
            'alasan_mendaftar' => 'Alasan Sudah Diperjelas dan Lengkap',
            'status'           => 'menunggu_verifikasi',
        ]);
    }
}
