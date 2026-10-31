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
        if (!Schema::hasTable('review_ukm')) {
            Schema::create('review_ukm', function (Blueprint $table) {
                $table->id();
                // Menggunakan signed bigInteger agar presisi dengan tipe data id pada tabel mahasiswa dan ukm
                $table->bigInteger('mahasiswa_id');
                $table->bigInteger('ukm_id');
                $table->unsignedTinyInteger('rating');
                $table->text('komentar');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->timestamps();

                // Foreign Key Constraints
                $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa')->cascadeOnDelete();
                $table->foreign('ukm_id')->references('id')->on('ukm')->cascadeOnDelete();

                // Satu mahasiswa hanya boleh memberikan SATU review untuk SATU UKM
                $table->unique(['mahasiswa_id', 'ukm_id'], 'unique_mhs_ukm_review');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_ukm');
    }
};
