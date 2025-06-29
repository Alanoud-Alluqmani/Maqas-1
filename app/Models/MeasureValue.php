<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeasureValue extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'measure',
        'measure_name_id',
        'measure_id'
    ];


    public function measure_name(){
        return $this->belongsTo(MeasureName::class);
    }

    public function measure(){
        return $this->belongsTo(Measure::class);
    }
}
