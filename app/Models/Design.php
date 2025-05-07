<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'price'
    ];


    public function images(){
        return $this->hasMany(Image::class);
    }

    public function items(){
        return $this->belongsToMany(Item::class);
    }

    public function feature_store(){
        return $this->belongsTo(FeatureStore::class);
    }
}
