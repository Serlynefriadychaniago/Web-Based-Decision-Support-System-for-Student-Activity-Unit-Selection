<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\ReviewUkm;
use App\Models\Ukm;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ReviewUkmDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Memulai seeding data Mahasiswa, Pendaftaran, dan Review UKM...');

        // 1. Data Mahasiswa Tambahan untuk memenuhi kebutuhan 10 anggota per UKM
        $additionalStudents = [
            ['nama' => 'Fajar Maulana', 'nim' => '220511030', 'prodi' => 'Teknik Informatika', 'fakultas' => 'Teknik', 'angkatan' => '2022', 'jk' => 'L'],
            ['nama' => 'Dinda Permatasari', 'nim' => '220511031', 'prodi' => 'Manajemen', 'fakultas' => 'Ekonomi & Bisnis', 'angkatan' => '2022', 'jk' => 'P'],
            ['nama' => 'Rizky Firmansyah', 'nim' => '220511032', 'prodi' => 'Ilmu Komunikasi', 'fakultas' => 'Ilmu Sosial & Ilmu Politik', 'angkatan' => '2023', 'jk' => 'L'],
            ['nama' => 'Anisa Rahmawati', 'nim' => '220511033', 'prodi' => 'Akuntansi', 'fakultas' => 'Ekonomi & Bisnis', 'angkatan' => '2022', 'jk' => 'P'],
            ['nama' => 'Bagas Aditya', 'nim' => '220511034', 'prodi' => 'Ilmu Hukum', 'fakultas' => 'Hukum', 'angkatan' => '2023', 'jk' => 'L'],
            ['nama' => 'Siti Nurhaliza', 'nim' => '220511035', 'prodi' => 'Farmasi', 'fakultas' => 'Ilmu Kesehatan', 'angkatan' => '2023', 'jk' => 'P'],
            ['nama' => 'Dimas Prasetyo', 'nim' => '220511036', 'prodi' => 'PGSD', 'fakultas' => 'Keguruan & Ilmu Pendidikan', 'angkatan' => '2022', 'jk' => 'L'],
            ['nama' => 'Nabila Azzahra', 'nim' => '220511037', 'prodi' => 'Pendidikan Bahasa Inggris', 'fakultas' => 'Keguruan & Ilmu Pendidikan', 'angkatan' => '2023', 'jk' => 'P'],
            ['nama' => 'Gilang Ramadhan', 'nim' => '220511038', 'prodi' => 'Teknik Informatika', 'fakultas' => 'Teknik', 'angkatan' => '2022', 'jk' => 'L'],
            ['nama' => 'Aulia Putri', 'nim' => '220511039', 'prodi' => 'Manajemen', 'fakultas' => 'Ekonomi & Bisnis', 'angkatan' => '2023', 'jk' => 'P'],
            ['nama' => 'Hendra Saputra', 'nim' => '220511040', 'prodi' => 'Ilmu Komunikasi', 'fakultas' => 'Ilmu Sosial & Ilmu Politik', 'angkatan' => '2022', 'jk' => 'L'],
            ['nama' => 'Tiara Andini', 'nim' => '220511041', 'prodi' => 'Farmasi', 'fakultas' => 'Ilmu Kesehatan', 'angkatan' => '2023', 'jk' => 'P'],
            ['nama' => 'Ilham Hidayat', 'nim' => '220511042', 'prodi' => 'Teknik Informatika', 'fakultas' => 'Teknik', 'angkatan' => '2022', 'jk' => 'L'],
            ['nama' => 'Maya Anggraini', 'nim' => '220511043', 'prodi' => 'Akuntansi', 'fakultas' => 'Ekonomi & Bisnis', 'angkatan' => '2023', 'jk' => 'P'],
            ['nama' => 'Reza Pratama', 'nim' => '220511044', 'prodi' => 'Ilmu Hukum', 'fakultas' => 'Hukum', 'angkatan' => '2022', 'jk' => 'L'],
            ['nama' => 'Fitri Handayani', 'nim' => '220511045', 'prodi' => 'PGSD', 'fakultas' => 'Keguruan & Ilmu Pendidikan', 'angkatan' => '2023', 'jk' => 'P'],
            ['nama' => 'Bayu Wicaksono', 'nim' => '220511046', 'prodi' => 'Teknik Informatika', 'fakultas' => 'Teknik', 'angkatan' => '2022', 'jk' => 'L'],
            ['nama' => 'Dewi Sartika', 'nim' => '220511047', 'prodi' => 'Manajemen', 'fakultas' => 'Ekonomi & Bisnis', 'angkatan' => '2023', 'jk' => 'P'],
            ['nama' => 'Aldi Nugraha', 'nim' => '220511048', 'prodi' => 'Ilmu Komunikasi', 'fakultas' => 'Ilmu Sosial & Ilmu Politik', 'angkatan' => '2022', 'jk' => 'L'],
            ['nama' => 'Putri Wulandari', 'nim' => '220511049', 'prodi' => 'Akuntansi', 'fakultas' => 'Ekonomi & Bisnis', 'angkatan' => '2023', 'jk' => 'P'],
            ['nama' => 'Arif Rahman', 'nim' => '220511050', 'prodi' => 'Farmasi', 'fakultas' => 'Ilmu Kesehatan', 'angkatan' => '2022', 'jk' => 'L'],
            ['nama' => 'Tania Oktaviani', 'nim' => '220511051', 'prodi' => 'PGSD', 'fakultas' => 'Keguruan & Ilmu Pendidikan', 'angkatan' => '2023', 'jk' => 'P'],
            ['nama' => 'Fikri Haikal', 'nim' => '220511052', 'prodi' => 'Teknik Informatika', 'fakultas' => 'Teknik', 'angkatan' => '2022', 'jk' => 'L'],
            ['nama' => 'Zahra Amelia', 'nim' => '220511053', 'prodi' => 'Ilmu Komunikasi', 'fakultas' => 'Ilmu Sosial & Ilmu Politik', 'angkatan' => '2023', 'jk' => 'P'],
        ];

        foreach ($additionalStudents as $data) {
            $existingMhs = Mahasiswa::where('nim', $data['nim'])->first();
            if (!$existingMhs) {
                $username = strtolower(str_replace(' ', '', $data['nama'])) . rand(10, 99);
                $user = User::create([
                    'name'     => $data['nama'],
                    'username' => $username,
                    'email'    => $username . '@student.umc.ac.id',
                    'password' => Hash::make('password'),
                    'role'     => 'mahasiswa',
                ]);

                Mahasiswa::create([
                    'user_id'       => $user->id,
                    'nim'           => $data['nim'],
                    'nama'          => $data['nama'],
                    'prodi'         => $data['prodi'],
                    'fakultas'      => $data['fakultas'],
                    'angkatan'      => $data['angkatan'],
                    'jenis_kelamin' => $data['jk'],
                    'no_hp'         => '08' . rand(1111111111, 9999999999),
                ]);
            }
        }

        // Ambil seluruh daftar mahasiswa yang tersedia
        $allMahasiswa = Mahasiswa::all();
        $this->command->info("Total Mahasiswa tersedia: " . $allMahasiswa->count());

        // 2. Daftar Ulasan Khas untuk Masing-masing UKM
        $reviewsPerUkm = [
            // UKM 2: Mapala Muci
            2 => [
                ['rating' => 5, 'komentar' => "Pengalaman mendaki gunung dan susur gua bersama Mapala Muci luar biasa seru. Mental dan kepemimpinan benar-benar terlatih di sini!"],
                ['rating' => 5, 'komentar' => "Kekeluargaan di Mapala Muci sangat erat. Senior selalu membimbing tentang survival alam bebas dan SOP keselamatan pendakian."],
                ['rating' => 5, 'komentar' => "Banyak belajar tentang navigasi darat dan konservasi alam. Sangat recommended bagi yang menyukai tantangan alam bebas."],
                ['rating' => 4, 'komentar' => "Kegiatan ekspedisi dan diksar mengajarkan disiplin tinggi. Fasilitas alat outdoor UKM juga sangat memadai."],
                ['rating' => 5, 'komentar' => "UKM yang sangat solid! Tidak hanya mendaki, kami juga sering mengadakan aksi bakti sosial dan penanaman pohon di Cirebon."],
                ['rating' => 5, 'komentar' => "Bagi saya yang awalnya awam tentang naik gunung, materi dasar di Mapala Muci sangat terstruktur dan aman."],
                ['rating' => 4, 'komentar' => "Pelatihan tali-temali dan rock climbing sangat seru. Tempat yang tepat untuk mengasah keberanian dan fisik."],
                ['rating' => 5, 'komentar' => "Pengalaman survival di hutan Gunung Ciremai menjadi momen paling berkesan selama kuliah di UMC. Salut untuk pengurus!"],
                ['rating' => 5, 'komentar' => "Organisasi yang sangat profesional dalam manajemen ekspedisi dan mitigasi risiko kegiatan luar ruang."],
                ['rating' => 5, 'komentar' => "Menyenangkan sekali bisa berorganisasi sambil menjelajahi keindahan alam Indonesia. Jangan ragu untuk gabung Mapala!"],
            ],
            // UKM 5: Computer Education
            5 => [
                ['rating' => 5, 'komentar' => "Workshop coding web dan IoT yang diadakan UKM ini sangat membantu meningkatkan skill praktis mahasiswa di luar jam kuliah."],
                ['rating' => 5, 'komentar' => "Mentor-mentornya sabar banget saat ngajarin dasar pemrograman dan troubleshooting hardware. Sangat bermanfaat!"],
                ['rating' => 4, 'komentar' => "Lingkungan belajarnya sangat suportif. Berkat UKM Computer Education, saya bisa ikut lomba software development antar kampus."],
                ['rating' => 5, 'komentar' => "Materi pelatihannya selalu up-to-date dengan kebutuhan industri teknologi saat ini seperti Laravel, Flutter, dan Python."],
                ['rating' => 5, 'komentar' => "Banyak project kolaborasi seru. Sangat direkomendasikan bagi mahasiswa yang ingin memperkaya portofolio tech."],
                ['rating' => 5, 'komentar' => "Kegiatan seminar IT dan bootcamp rutinnya benar-benar membuka wawasan tentang dunia kerja IT masa depan."],
                ['rating' => 4, 'komentar' => "Komunitas yang sangat asyik untuk sharing seputar teknologi, web design, dan perbaikan perangkat komputer."],
                ['rating' => 5, 'komentar' => "Selain hard skill koding, kami juga diajarkan kerja sama tim dan manajemen proyek aplikasi skala kecil."],
                ['rating' => 5, 'komentar' => "Fasilitas lab komputer dan dukungan pengurus untuk riset mandiri sangat baik. Top banget UKM ini!"],
                ['rating' => 5, 'komentar' => "Sangat bersyukur bisa bergabung dengan Computer Education, wawasan teknologi saya berkembang jauh lebih cepat."],
            ],
            // UKM 6: PSM (Paduan Suara Mahasiswa)
            6 => [
                ['rating' => 5, 'komentar' => "Pelatih vokalnya sangat profesional dan teliti. Teknik pernapasan serta harmonisasi suara saya meningkat pesat."],
                ['rating' => 5, 'komentar' => "Suasana latihan selalu menyenangkan meski latihannya intensif menjelang acara wisuda dan kompetisi paduan suara."],
                ['rating' => 5, 'komentar' => "Bangga bisa tampil di panggung wisuda universitas dan berbagai festival paduan suara daerah bersama PSM UMC!"],
                ['rating' => 4, 'komentar' => "Rasa kekeluargaan di paduan suara sangat hangat. Setiap anggota saling mendukung untuk menghasilkan harmoni terbaik."],
                ['rating' => 5, 'komentar' => "Membantu meningkatkan kepercayaan diri saya untuk bernyanyi di depan publik. Sangat cocok bagi pecinta seni vokal."],
                ['rating' => 5, 'komentar' => "Jadwal latihannya disiplin dan teratur, membuat kami terbiasa membagi waktu antara kuliah dan hobi bernyanyi."],
                ['rating' => 4, 'komentar' => "Pengalaman tampil di berbagai acara resmi universitas memberikan kebanggaan tersendiri sebagai mahasiswa UMC."],
                ['rating' => 5, 'komentar' => "Bagi yang belum pernah ikut paduan suara jangan takut, karena di sini diajarkan dari dasar solmisasi dan artikulasi."],
                ['rating' => 5, 'komentar' => "Kostum dan aransemen lagunya selalu keren dan berkelas. Sangat bangga menjadi bagian dari PSM!"],
                ['rating' => 5, 'komentar' => "Pengalaman berharga bisa bernyanyi bersama puluhan suara yang selaras dalam satu harmoni yang indah."],
            ],
            // UKM 7: Teater Roempoet
            7 => [
                ['rating' => 5, 'komentar' => "Latihan olah tubuh, vokal, dan emosi di Teater Roempoet benar-benar membantu saya mengasah public speaking dan percaya diri."],
                ['rating' => 5, 'komentar' => "Pentas produksi teater tahunan sangat megah! Proses di balik layar mulai dari naskah, tata panggung, hingga akting sangat berkesan."],
                ['rating' => 4, 'komentar' => "Di sini kami belajar menjadi pribadi yang ekspresif, peka terhadap isu sosial, dan kreatif dalam menyampaikan pesan moral."],
                ['rating' => 5, 'komentar' => "Keluarga kedua di kampus. Pengurus dan anggotanya sangat terbuka menyambut ide-ide artistik baru."],
                ['rating' => 5, 'komentar' => "Seni peran yang diajarkan sangat mendalam. Tidak hanya akting, tapi juga belajar tata artistik, lighting, dan musik pengiring."],
                ['rating' => 5, 'komentar' => "Menghadiri festival teater kampus se-Jawa Barat bersama Teater Roempoet adalah kenangan paling berharga."],
                ['rating' => 4, 'komentar' => "Tempat terbaik untuk melepas penat kuliah dengan berkarya seni yang bermakna bagi penonton."],
                ['rating' => 5, 'komentar' => "Suasana pementasan di gedung pertunjukan selalu bikin merinding. Keren banget dedikasi semua tim produksi!"],
                ['rating' => 5, 'komentar' => "Setiap anggota dihargai bakat uniknya, baik yang ingin di depan panggung sebagai aktor maupun di belakang panggung."],
                ['rating' => 5, 'komentar' => "UKM teater yang legendaris di UMC dengan karya-karya panggung yang selalu memukau penonton!"],
            ],
            // UKM 8: Olahraga
            8 => [
                ['rating' => 5, 'komentar' => "Fasilitas latihan futsal dan basket sangat memadai. Latihan rutin setiap minggu selalu ramai dan seru!"],
                ['rating' => 5, 'komentar' => "Banyak cabang olahraga yang aktif, mulai dari futsal, badminton, basket, hingga voli. Semua mahasiswa bisa menyalurkan hobi."],
                ['rating' => 4, 'komentar' => "Kompetisi internal antar fakultas yang diadakan UKM Olahraga sangat meriah dan menjunjung tinggi sportivitas."],
                ['rating' => 5, 'komentar' => "Pelatihannya intensif dan membantu menjaga kebugaran fisik di tengah padatnya tugas perkuliahan."],
                ['rating' => 5, 'komentar' => "Kekompakan tim sangat terasa saat kami mewakili kampus dalam turnamen olahraga antar universitas di Cirebon."],
                ['rating' => 4, 'komentar' => "Tempat yang pas untuk cari relasi lintas jurusan sambil berolahraga sehat bersama rekan-rekan mahasiswa."],
                ['rating' => 5, 'komentar' => "Pengurus UKM sangat suportif dalam memfasilitasi kebutuhan peralatan olahraga anggota."],
                ['rating' => 5, 'komentar' => "Bagi yang ingin mengukir prestasi non-akademik di bidang olahraga, UKM ini adalah wadah yang paling tepat."],
                ['rating' => 5, 'komentar' => "Suasana latihan sangat kompetitif namun tetap penuh kebersamaan dan keceriaan."],
                ['rating' => 5, 'komentar' => "Sangat puas bergabung di UKM Olahraga UMC. Tubuh sehat, teman bertambah, dan sering juara turnamen!"],
            ],
            // UKM 9: Marching Band
            9 => [
                ['rating' => 5, 'komentar' => "Disiplin tinggi, baris-berbaris yang presisi, dan musik yang rancak! Pengalaman luar biasa bisa gabung Marching Band UMC."],
                ['rating' => 5, 'komentar' => "Alat musik brass dan perkusi sangat lengkap dan dirawat dengan sangat baik oleh pengurus."],
                ['rating' => 5, 'komentar' => "Tampil di parade hari kemerdekaan dan acara besar universitas selalu menjadi kebanggaan tersendiri bagi kami."],
                ['rating' => 4, 'komentar' => "Pelatihnya sangat sabar mengajarkan pembacaan partitur nada dan formasi display marching di lapangan."],
                ['rating' => 5, 'komentar' => "Rasa kebersamaan terjalin sangat erat karena setiap gerakan dan nada membutuhkan kerja sama tim yang sempurna."],
                ['rating' => 5, 'komentar' => "Latihan fisik dan formasi langkah memang menantang, tapi hasilnya sangat memuaskan saat tampil di depan ribuan orang."],
                ['rating' => 4, 'komentar' => "Color guard-nya atraktif dan koreografinya selalu kreatif mengikuti perkembangan musik marching band modern."],
                ['rating' => 5, 'komentar' => "Menambah kedisiplinan dan manajemen waktu yang sangat baik bagi kehidupan perkuliahan saya."],
                ['rating' => 5, 'komentar' => "Kostum seragam marching band sangat gagah dan penampilannya selalu ditunggu-tunggu setiap ada event kampus."],
                ['rating' => 5, 'komentar' => "Bangga menjadi bagian dari Marching Band Gita Surya UMC. Semangat juang dan musikalitas kami terus terasah!"],
            ],
            // UKM 14: PIK-M
            14 => [
                ['rating' => 5, 'komentar' => "UKM yang sangat bermanfaat untuk mengedukasi mahasiswa mengenai kesehatan mental dan pola hidup sehat remaja."],
                ['rating' => 5, 'komentar' => "Pelatihan konselor sebaya sangat membuka empati dan kemampuan mendengarkan secara aktif tanpa menghakimi."],
                ['rating' => 5, 'komentar' => "Banyak workshop dan talkshow inspiratif seputar self-love, manajemen stres kuliah, dan perencanaan masa depan."],
                ['rating' => 4, 'komentar' => "Lingkungan anggotanya sangat hangat, positif, dan saling mendukung saat ada mahasiswa yang butuh teman curhat."],
                ['rating' => 5, 'komentar' => "Kegiatan sosialisasi ke sekolah-sekolah dan masyarakat Cirebon membuat kami merasa memberikan kontribusi nyata."],
                ['rating' => 5, 'komentar' => "Bagus sekali untuk melatih keterampilan komunikasi interpersonal dan kepedulian terhadap sesama mahasiswa."],
                ['rating' => 4, 'komentar' => "Materi tentang kesehatan reproduksi remaja dan pencegahan kenakalan disampaikan dengan cara yang edukatif dan santai."],
                ['rating' => 5, 'komentar' => "Sangat bersyukur ada PIK-M di UMC sebagai ruang aman bagi mahasiswa untuk bercerita dan mencari solusi bersama."],
                ['rating' => 5, 'komentar' => "Pengurusnya ramah-ramah dan program kerjanya selalu relevan dengan tantangan generasi muda saat ini."],
                ['rating' => 5, 'komentar' => "Wadah pengembangan diri yang luar biasa. Sangat merekomendasikan mahasiswa baru untuk bergabung dengan PIK-M!"],
            ],
        ];

        // 3. Proses Seeding: Pastikan Pendaftaran & Review untuk Setiap UKM
        $allUkm = Ukm::whereIn('id', array_keys($reviewsPerUkm))->get();
        $mhsList = $allMahasiswa->values();
        $mhsIndex = 0;

        foreach ($allUkm as $ukm) {
            $this->command->info("Memproses UKM: {$ukm->nama_ukm} (ID: {$ukm->id})");
            $ukmReviews = $reviewsPerUkm[$ukm->id];

            for ($i = 0; $i < 10; $i++) {
                // Pilih mahasiswa secara berurutan / berputar agar bervariasi
                $targetMhs = $mhsList[($mhsIndex + $i) % $mhsList->count()];

                // A. Pastikan Mahasiswa Terdaftar di UKM ini
                $reg = DB::table('pendaftaran_ukm')
                    ->where('mahasiswa_id', $targetMhs->id)
                    ->where('ukm_id', $ukm->id)
                    ->first();

                if (!$reg) {
                    $regNumber = 'REG-' . $ukm->id . '-' . date('Ymd') . '-' . str_pad($targetMhs->id, 4, '0', STR_PAD_LEFT);
                    DB::table('pendaftaran_ukm')->insert([
                        'nomor_pendaftaran'  => $regNumber,
                        'mahasiswa_id'       => $targetMhs->id,
                        'ukm_id'             => $ukm->id,
                        'alasan_mendaftar'   => "Ingin mengembangkan potensi diri dan berpartisipasi aktif dalam kegiatan {$ukm->nama_ukm}.",
                        'persetujuan'        => 1,
                        'status'             => 'terverifikasi',
                        'tanggal_daftar'     => now()->subDays(rand(10, 60)),
                        'tanggal_verifikasi' => now()->subDays(rand(1, 9)),
                    ]);
                }

                // B. Buat / Update Review untuk Mahasiswa ini
                $revData = $ukmReviews[$i];
                $createdDate = now()->subDays(rand(1, 30))->subHours(rand(1, 23))->subMinutes(rand(1, 59));

                ReviewUkm::updateOrCreate(
                    [
                        'mahasiswa_id' => $targetMhs->id,
                        'ukm_id'       => $ukm->id,
                    ],
                    [
                        'rating'     => $revData['rating'],
                        'komentar'   => $revData['komentar'],
                        'status'     => 'approved',
                        'created_at' => $createdDate,
                        'updated_at' => $createdDate,
                    ]
                );
            }

            // Geser index mahasiswa agar setiap UKM mendapatkan kombinasi mahasiswa yang dinamis
            $mhsIndex += 3;
        }

        // 4. Tambahkan 2 review berstatus pending dari mahasiswa LAIN untuk demonstrasi fitur moderasi Admin
        if ($mhsList->count() >= 30) {
            $pendingMhs1 = $mhsList[28]; // Mahasiswa yang belum me-review UKM 5
            $pendingUkm1 = 5; // Computer Education
            $reg1 = DB::table('pendaftaran_ukm')
                ->where('mahasiswa_id', $pendingMhs1->id)
                ->where('ukm_id', $pendingUkm1)
                ->first();
            if (!$reg1) {
                DB::table('pendaftaran_ukm')->insert([
                    'nomor_pendaftaran'  => 'REG-' . $pendingUkm1 . '-' . date('Ymd') . '-' . $pendingMhs1->id,
                    'mahasiswa_id'       => $pendingMhs1->id,
                    'ukm_id'             => $pendingUkm1,
                    'alasan_mendaftar'   => 'Tertarik belajar pengembangan aplikasi modern.',
                    'status'             => 'terverifikasi',
                    'persetujuan'        => 1,
                    'tanggal_daftar'     => now()->subDays(2),
                    'tanggal_verifikasi' => now()->subDays(1),
                ]);
            }
            ReviewUkm::updateOrCreate(
                ['mahasiswa_id' => $pendingMhs1->id, 'ukm_id' => $pendingUkm1],
                [
                    'rating'     => 5,
                    'komentar'   => 'Baru saja bergabung dan mengikuti pertemuan perdana, materinya sangat menarik dan kakak tingkatnya ramah-ramah.',
                    'status'     => 'pending',
                    'created_at' => now()->subHours(2),
                    'updated_at' => now()->subHours(2),
                ]
            );

            $pendingMhs2 = $mhsList[29]; // Mahasiswa yang belum me-review UKM 2
            $pendingUkm2 = 2; // Mapala Muci
            $reg2 = DB::table('pendaftaran_ukm')
                ->where('mahasiswa_id', $pendingMhs2->id)
                ->where('ukm_id', $pendingUkm2)
                ->first();
            if (!$reg2) {
                DB::table('pendaftaran_ukm')->insert([
                    'nomor_pendaftaran'  => 'REG-' . $pendingUkm2 . '-' . date('Ymd') . '-' . $pendingMhs2->id,
                    'mahasiswa_id'       => $pendingMhs2->id,
                    'ukm_id'             => $pendingUkm2,
                    'alasan_mendaftar'   => 'Ingin menyalurkan hobi naik gunung dan pelestarian alam.',
                    'status'             => 'terverifikasi',
                    'persetujuan'        => 1,
                    'tanggal_daftar'     => now()->subDays(3),
                    'tanggal_verifikasi' => now()->subDays(2),
                ]);
            }
            ReviewUkm::updateOrCreate(
                ['mahasiswa_id' => $pendingMhs2->id, 'ukm_id' => $pendingUkm2],
                [
                    'rating'     => 5,
                    'komentar'   => 'Pendaftaran cepat dan sambutan anggota senior sangat hangat. Siap mengikuti latihan dasar.',
                    'status'     => 'pending',
                    'created_at' => now()->subMinutes(45),
                    'updated_at' => now()->subMinutes(45),
                ]
            );
        }

        $this->command->info('Seeding berhasil! Seluruh UKM kini memiliki 10 ulasan approved beserta beberapa sample pending untuk moderasi.');
    }
}
