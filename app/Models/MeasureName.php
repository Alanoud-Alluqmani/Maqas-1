<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeasureName extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en'
    ];


    public function secondary_measures(){
        return $this->hasMany(SecondaryMeasure::class);
    }
}
