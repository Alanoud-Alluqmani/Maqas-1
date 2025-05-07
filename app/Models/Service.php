<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en'
    ];


    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function stores(){
        return $this->belongsToMany(Store::class);
    }
}
