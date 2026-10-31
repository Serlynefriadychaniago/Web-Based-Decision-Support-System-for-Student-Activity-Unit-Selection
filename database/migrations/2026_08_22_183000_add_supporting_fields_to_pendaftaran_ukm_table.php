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
        Schema::table('pendaftaran_ukm', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftaran_ukm', 'ketertarikan')) {
                $table->text('ketertarikan')->nullable()->after('alasan_mendaftar');
            }
            if (!Schema::hasColumn('pendaftaran_ukm', 'pengalaman')) {
                $table->string('pengalaman', 100)->nullable()->after('ketertarikan');
            }
            if (!Schema::hasColumn('pendaftaran_ukm', 'detail_pengalaman')) {
                $table->text('detail_pengalaman')->nullable()->after('pengalaman');
            }
            if (!Schema::hasColumn('pendaftaran_ukm', 'keterampilan')) {
                $table->text('keterampilan')->nullable()->after('detail_pengalaman');
            }
            if (!Schema::hasColumn('pendaftaran_ukm', 'kesediaan_mengikuti_kegiatan')) {
                $table->string('kesediaan_mengikuti_kegiatan', 100)->nullable()->after('keterampilan');
            }
            if (!Schema::hasColumn('pendaftaran_ukm', 'kesesuaian_jadwal')) {
                $table->string('kesesuaian_jadwal', 100)->nullable()->after('kesediaan_mengikuti_kegiatan');
            }
            if (!Schema::hasColumn('pendaftaran_ukm', 'kontribusi')) {
                $table->text('kontribusi')->nullable()->after('kesesuaian_jadwal');
            }
            if (!Schema::hasColumn('pendaftaran_ukm', 'kesediaan_menaati_aturan')) {
                $table->string('kesediaan_menaati_aturan', 100)->nullable()->after('kontribusi');
            }
            if (!Schema::hasColumn('pendaftaran_ukm', 'komitmen_aktif')) {
                $table->string('komitmen_aktif', 100)->nullable()->after('kesediaan_menaati_aturan');
            }
            if (!Schema::hasColumn('pendaftaran_ukm', 'persetujuan_data')) {
                $table->boolean('persetujuan_data')->default(true)->after('komitmen_aktif');
            }
            if (!Schema::hasColumn('pendaftaran_ukm', 'persetujuan_keputusan_ukm')) {
                $table->boolean('persetujuan_keputusan_ukm')->default(true)->after('persetujuan_data');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran_ukm', function (Blueprint $table) {
            $columns = [
                'ketertarikan',
                'pengalaman',
                'detail_pengalaman',
                'keterampilan',
                'kesediaan_mengikuti_kegiatan',
                'kesesuaian_jadwal',
                'kontribusi',
                'kesediaan_menaati_aturan',
                'komitmen_aktif',
                'persetujuan_data',
                'persetujuan_keputusan_ukm',
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('pendaftaran_ukm', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
