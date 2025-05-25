<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Design extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'name_ar',
        'name_en',
        'price',
        'feature_store_id',
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
