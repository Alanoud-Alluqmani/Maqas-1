<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeasureName extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'name_ar',
        'name_en'
    ];


    public function secondary_measures(){
        return $this->hasMany(MeasureValue::class);
    }
}
