<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeasureName extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name_ar',
        'name_en'
    ];


    public function secondary_measures(){
        return $this->hasMany(MeasureValue::class);
    }
}
