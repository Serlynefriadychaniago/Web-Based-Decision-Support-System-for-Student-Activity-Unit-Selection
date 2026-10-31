<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubKriteria extends Model
{
    use HasFactory;

    protected $table = 'sub_kriteria';

    protected $fillable = [
        'kriteria_id',
        'nama_subkriteria',
        'urutan',
        'status',
    ];

    /**
     * Sub-kriteria dimiliki oleh satu kriteria.
     */
    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(
            Kriteria::class,
            'kriteria_id'
        );
    }

    /**
     * Sub-kriteria dapat digunakan
     * pada banyak profil kriteria UKM.
     */
    public function ukmKriteriaProfiles(): HasMany
    {
        return $this->hasMany(
            UkmKriteriaProfile::class,
            'sub_kriteria_id'
        );
    }
}