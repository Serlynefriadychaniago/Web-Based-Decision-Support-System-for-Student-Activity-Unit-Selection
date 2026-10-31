<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ukm extends Model
{
    use HasFactory;

    protected $table = 'ukm';

    protected $fillable = [
        'nama_ukm',
        'deskripsi',
        'visi',
        'misi',
        'logo',
        'pembina',
        'ketua',
        'kontak',
        'email',
        'instagram',
        'lokasi_sekretariat',
        'hari_latihan',
        'status',
    ];

    /**
     * Relasi ke profil kriteria UKM.
     *
     * Satu UKM dapat memiliki banyak
     * profil kriteria.
     */
    public function kriteriaProfiles(): HasMany
    {
        return $this->hasMany(
            UkmKriteriaProfile::class,
            'ukm_id'
        );
    }

    public function profiles(): HasMany
    {
        return $this->hasMany(
            UkmKriteriaProfile::class,
            'ukm_id'
        );
    }

    /**
     * Relasi ke seluruh data review UKM.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ReviewUkm::class, 'ukm_id');
    }

    /**
     * Relasi ke review UKM yang berstatus approved.
     */
    public function approvedReviews(): HasMany
    {
        return $this->hasMany(ReviewUkm::class, 'ukm_id')->where('status', 'approved');
    }

    /**
     * Accessor rata-rata rating review yang approved (float 1 desimal).
     */
    public function getAverageRatingAttribute(): float
    {
        $avg = $this->approvedReviews()->avg('rating');
        return $avg ? round((float)$avg, 1) : 0.0;
    }

    /**
     * Accessor jumlah review yang approved.
     */
    public function getReviewsCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Accessor untuk mendapatkan URL Instagram yang valid (hyperlink).
     */
    public function getInstagramUrlAttribute(): ?string
    {
        $val = trim($this->instagram ?? '');
        if (empty($val)) {
            return null;
        }

        if (!preg_match('/^https?:\/\//i', $val)) {
            $username = ltrim($val, '@');
            $username = explode('?', $username)[0];
            $username = rtrim($username, '/');
            return 'https://www.instagram.com/' . $username . '/';
        }

        return $val;
    }

    /**
     * Accessor untuk meng-ekstrak username Instagram secara dinamis (contoh: @ukm_umc).
     */
    public function getInstagramUsernameAttribute(): ?string
    {
        $val = trim($this->instagram ?? '');
        if (empty($val)) {
            return null;
        }

        // Jika hanya berisi username tanpa URL (misal: @ukm_umc atau ukm_umc)
        if (!preg_match('/^https?:\/\//i', $val)) {
            $clean = ltrim($val, '@');
            $clean = explode('?', $clean)[0];
            $clean = rtrim($clean, '/');
            return '@' . $clean;
        }

        // Parse URL untuk mengambil path
        $path = parse_url($val, PHP_URL_PATH);
        if (!$path) {
            return null;
        }

        $segments = array_values(array_filter(explode('/', trim($path, '/'))));
        if (empty($segments)) {
            return null;
        }

        $username = $segments[0];
        $username = explode('?', $username)[0];
        $username = ltrim($username, '@');

        return !empty($username) ? '@' . $username : null;
    }
}