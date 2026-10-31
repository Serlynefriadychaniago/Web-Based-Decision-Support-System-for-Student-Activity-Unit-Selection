<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AhpPairwise extends Model
{
    use HasFactory;

    protected $table = 'ahp_pairwise';

    /**
     * Tabel ahp_pairwise tidak memiliki
     * created_at dan updated_at.
     */
    public $timestamps = false;

    protected $fillable = [
        'ahp_run_id',
        'kriteria_row_id',
        'kriteria_col_id',
        'nilai',
    ];

    protected $casts = [
        'nilai' => 'float',
    ];

    /**
     * Proses AHP yang memiliki data pairwise ini.
     */
    public function ahpRun(): BelongsTo
    {
        return $this->belongsTo(
            AhpRun::class,
            'ahp_run_id'
        );
    }

    /**
     * Kriteria pada baris.
     */
    public function kriteriaRow(): BelongsTo
    {
        return $this->belongsTo(
            Kriteria::class,
            'kriteria_row_id'
        );
    }

    /**
     * Kriteria pada kolom.
     */
    public function kriteriaCol(): BelongsTo
    {
        return $this->belongsTo(
            Kriteria::class,
            'kriteria_col_id'
        );
    }
}