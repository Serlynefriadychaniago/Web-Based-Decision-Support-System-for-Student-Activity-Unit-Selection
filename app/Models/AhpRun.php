<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AhpRun extends Model
{
    use HasFactory;

    protected $table = 'ahp_runs';

    /**
     * Karena tabel ahp_runs hanya memiliki
     * created_at dan tidak memiliki updated_at.
     */
    const UPDATED_AT = null;

    protected $fillable = [
        'created_by',
        'lambda_max',
        'ci',
        'ri',
        'cr',
        'status',
    ];

    protected $casts = [
        'lambda_max' => 'float',
        'ci' => 'float',
        'ri' => 'float',
        'cr' => 'float',
    ];

    /**
     * User yang membuat proses AHP.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /**
     * Data perbandingan berpasangan.
     */
    public function pairwise(): HasMany
    {
        return $this->hasMany(
            AhpPairwise::class,
            'ahp_run_id'
        );
    }

    /**
     * Bobot kriteria hasil AHP.
     */
    public function weights(): HasMany
    {
        return $this->hasMany(
            AhpWeight::class,
            'ahp_run_id'
        );
    }
}