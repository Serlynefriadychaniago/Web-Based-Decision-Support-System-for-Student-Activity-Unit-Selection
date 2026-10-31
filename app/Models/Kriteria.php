<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = [
        'kode',
        'nama_kriteria',
        'tipe',
    ];

    /**
     * Relasi ke sub-kriteria.
     *
     * Satu kriteria dapat memiliki banyak sub-kriteria.
     */
    public function subKriteria(): HasMany
    {
        return $this->hasMany(
            SubKriteria::class,
            'kriteria_id'
        );
    }

    /**
     * Relasi ke profil kriteria UKM.
     *
     * Satu kriteria dapat digunakan
     * pada banyak profil UKM.
     */
    public function ukmKriteriaProfiles(): HasMany
    {
        return $this->hasMany(
            UkmKriteriaProfile::class,
            'kriteria_id'
        );
    }
}