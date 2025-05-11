<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Design extends Model
{
    use SoftDeletes;
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
