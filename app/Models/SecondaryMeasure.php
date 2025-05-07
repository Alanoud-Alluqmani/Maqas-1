<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecondaryMeasure extends Model
{
    protected $fillable = [
        'measure',
    ];


    public function measure_name(){
        return $this->belongsTo(MeasureName::class);
    }

    public function measure(){
        return $this->belongsTo(Measure::class);
    }
}
