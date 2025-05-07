<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en'
    ];


    public function stores(){
        return $this->hasMany(Store::class);
    }

    public function Features(){
        return $this->hasMany(Feature::class);
    }
}
