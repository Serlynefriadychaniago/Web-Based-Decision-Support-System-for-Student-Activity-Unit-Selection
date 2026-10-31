<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class ReviewUkm extends Model
{
    use HasFactory;

    protected $table = 'review_ukm';

    protected $fillable = [
        'mahasiswa_id',
        'ukm_id',
        'rating',
        'komentar',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Relasi ke model Mahasiswa.
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    /**
     * Relasi ke model Ukm.
     */
    public function ukm(): BelongsTo
    {
        return $this->belongsTo(Ukm::class, 'ukm_id');
    }

    /**
     * Scope untuk review yang sudah disetujui (approved).
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope untuk review yang menunggu moderasi (pending).
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk review yang ditolak (rejected).
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }
}
