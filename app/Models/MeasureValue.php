<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeasureValue extends Model
{
    use SoftDeletes;
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
