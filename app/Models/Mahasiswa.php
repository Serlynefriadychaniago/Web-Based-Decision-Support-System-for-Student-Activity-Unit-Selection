<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $fillable = [
        'user_id',
        'nim',
        'nama',
        'prodi',
        'fakultas',
        'angkatan',
        'jenis_kelamin',
        'no_hp',
        'foto',
    ];

    /**
     * Relasi ke tabel users
     * Setiap mahasiswa dimiliki oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tabel review_ukm
     * Satu mahasiswa dapat memberikan review ke berbagai UKM.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ReviewUkm::class, 'mahasiswa_id');
    }
}