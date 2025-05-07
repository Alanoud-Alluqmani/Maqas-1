<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en'
    ];

    public function stores(){
        return $this->belongsToMany(Store::class);
    }


    public function product_category(){
        return $this->belongsTo(ProductCategory::class);
    }
}
