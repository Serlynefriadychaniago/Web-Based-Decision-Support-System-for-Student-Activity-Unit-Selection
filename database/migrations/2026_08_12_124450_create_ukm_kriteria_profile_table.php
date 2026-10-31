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
        Schema::create('ukm_kriteria_profile', function (Blueprint $table) {
            $table->id();

            // UKM yang dinilai
            $table->foreignId('ukm_id')
                ->constrained('ukm')
                ->cascadeOnDelete();

            // Kriteria yang dinilai
            $table->foreignId('kriteria_id')
                ->constrained('kriteria')
                ->cascadeOnDelete();

            // Sub-kriteria, boleh kosong untuk kriteria
            // yang belum menggunakan sub-kriteria
            $table->foreignId('sub_kriteria_id')
                ->nullable()
                ->constrained('sub_kriteria')
                ->nullOnDelete();

            // Nilai relevansi UKM terhadap kriteria/sub-kriteria
            $table->decimal('nilai', 5, 2);

            $table->timestamps();

            // Satu kombinasi UKM + kriteria + sub-kriteria hanya boleh satu kali
            $table->unique(
                ['ukm_id', 'kriteria_id', 'sub_kriteria_id'],
                'ukm_kriteria_profile_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ukm_kriteria_profile');
    }
};