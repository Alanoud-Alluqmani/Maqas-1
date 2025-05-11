<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Measure extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function items(){
        return $this->hasMany(Item::class);
    }

    public function secondary_measures(){
        return $this->hasMany(MeasureValue::class);
    }
}
