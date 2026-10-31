<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AhpWeight extends Model
{
    use HasFactory;

    protected $table = 'ahp_weights';

    /**
     * Tabel ahp_weights tidak memiliki
     * created_at dan updated_at.
     */
    public $timestamps = false;

    protected $fillable = [
        'ahp_run_id',
        'kriteria_id',
        'bobot',
    ];

    protected $casts = [
        'bobot' => 'float',
    ];

    /**
     * Proses AHP yang menghasilkan bobot ini.
     */
    public function ahpRun(): BelongsTo
    {
        return $this->belongsTo(
            AhpRun::class,
            'ahp_run_id'
        );
    }

    /**
     * Kriteria yang memiliki bobot.
     */
    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(
            Kriteria::class,
            'kriteria_id'
        );
    }
}