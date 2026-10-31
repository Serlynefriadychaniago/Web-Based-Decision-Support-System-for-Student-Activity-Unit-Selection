<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('mahasiswa')) {
            Schema::create('mahasiswa', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('nim', 20)->unique();
                $table->string('nama', 100);
                $table->string('prodi', 100)->nullable();
                $table->string('fakultas', 100)->nullable();
                $table->string('angkatan', 4)->nullable();
                $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
                $table->string('no_hp', 20)->nullable();
                $table->string('foto', 255)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('ukm')) {
            Schema::create('ukm', function (Blueprint $table) {
                $table->id();
                $table->string('nama_ukm', 100);
                $table->text('deskripsi')->nullable();
                $table->text('visi')->nullable();
                $table->text('misi')->nullable();
                $table->string('logo', 255)->nullable();
                $table->string('pembina', 100)->nullable();
                $table->string('ketua', 100)->nullable();
                $table->string('kontak', 30)->nullable();
                $table->string('email', 100)->nullable();
                $table->string('instagram', 100)->nullable();
                $table->string('lokasi_sekretariat', 150)->nullable();
                $table->string('hari_latihan', 100)->nullable();
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kriteria')) {
            Schema::create('kriteria', function (Blueprint $table) {
                $table->id();
                $table->string('kode', 10)->nullable();
                $table->string('nama_kriteria', 50)->nullable();
                $table->enum('tipe', ['benefit', 'cost'])->default('benefit');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('sub_kriteria')) {
            Schema::create('sub_kriteria', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kriteria_id')->constrained('kriteria')->cascadeOnDelete();
                $table->string('nama_subkriteria', 150);
                $table->integer('urutan')->nullable();
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('questionnaire_sessions')) {
            Schema::create('questionnaire_sessions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
                $table->enum('status', ['draft', 'completed'])->default('draft');
                $table->dateTime('started_at')->nullable();
                $table->dateTime('completed_at')->nullable();
            });
        }

        if (!Schema::hasTable('questionnaire_answers')) {
            Schema::create('questionnaire_answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('session_id')->constrained('questionnaire_sessions')->cascadeOnDelete();
                $table->foreignId('kriteria_id')->constrained('kriteria')->cascadeOnDelete();
                $table->foreignId('sub_kriteria_id')->constrained('sub_kriteria')->cascadeOnDelete();
                $table->integer('ranking')->nullable();
                $table->decimal('nilai', 10, 2)->nullable();
            });
        }

        if (!Schema::hasTable('ahp_runs')) {
            Schema::create('ahp_runs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
                $table->decimal('lambda_max', 10, 6)->nullable();
                $table->decimal('ci', 10, 6)->nullable();
                $table->decimal('ri', 10, 6)->nullable();
                $table->decimal('cr', 10, 6)->nullable();
                $table->enum('status', ['valid', 'invalid'])->nullable();
                $table->timestamp('created_at')->nullable();
            });
        }

        if (!Schema::hasTable('topsis_runs')) {
            Schema::create('topsis_runs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
                $table->foreignId('questionnaire_session_id')->constrained('questionnaire_sessions')->cascadeOnDelete();
                $table->foreignId('ahp_run_id')->constrained('ahp_runs')->cascadeOnDelete();
                $table->timestamp('created_at')->nullable();
            });
        }

        if (!Schema::hasTable('topsis_results')) {
            Schema::create('topsis_results', function (Blueprint $table) {
                $table->id();
                $table->foreignId('topsis_run_id')->constrained('topsis_runs')->cascadeOnDelete();
                $table->foreignId('ukm_id')->constrained('ukm')->cascadeOnDelete();
                $table->decimal('s_plus', 10, 6)->nullable();
                $table->decimal('s_minus', 10, 6)->nullable();
                $table->decimal('nilai_preferensi', 10, 6)->nullable();
                $table->integer('ranking')->nullable();
            });
        }

        if (!Schema::hasTable('pendaftaran_ukm')) {
            Schema::create('pendaftaran_ukm', function (Blueprint $table) {
                $table->id();
                $table->string('nomor_pendaftaran', 30)->unique();
                $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
                $table->foreignId('ukm_id')->constrained('ukm')->cascadeOnDelete();
                $table->foreignId('topsis_result_id')->nullable()->constrained('topsis_results')->nullOnDelete();
                $table->text('alasan_mendaftar')->nullable();
                $table->boolean('persetujuan')->default(true);
                $table->string('status', 50)->default('menunggu_verifikasi');
                $table->dateTime('tanggal_daftar')->nullable();
                $table->dateTime('tanggal_verifikasi')->nullable();
            });
        }

        if (!Schema::hasTable('riwayat_status_pendaftaran')) {
            Schema::create('riwayat_status_pendaftaran', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pendaftaran_id')->constrained('pendaftaran_ukm')->cascadeOnDelete();
                $table->string('status', 50)->nullable();
                $table->text('catatan')->nullable();
                $table->foreignId('diubah_oleh')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('created_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_status_pendaftaran');
        Schema::dropIfExists('pendaftaran_ukm');
        Schema::dropIfExists('topsis_results');
        Schema::dropIfExists('topsis_runs');
        Schema::dropIfExists('ahp_runs');
        Schema::dropIfExists('questionnaire_answers');
        Schema::dropIfExists('questionnaire_sessions');
        Schema::dropIfExists('sub_kriteria');
        Schema::dropIfExists('kriteria');
        Schema::dropIfExists('ukm');
        Schema::dropIfExists('mahasiswa');
    }
};
