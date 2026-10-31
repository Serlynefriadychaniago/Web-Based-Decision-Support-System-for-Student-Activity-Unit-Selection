<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UkmKriteriaProfile extends Model
{
    protected $table = 'ukm_kriteria_profile';

    public $timestamps = false;

    protected $fillable = [
        'ukm_id',
        'kriteria_id',
        'sub_kriteria_id',
        'nilai',
    ];

    protected $casts = [
        'nilai' => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function ukm()
    {
        return $this->belongsTo(
            Ukm::class,
            'ukm_id'
        );
    }

    public function kriteria()
    {
        return $this->belongsTo(
            Kriteria::class,
            'kriteria_id'
        );
    }

    public function subKriteria()
    {
        return $this->belongsTo(
            SubKriteria::class,
            'sub_kriteria_id'
        );
    }
}