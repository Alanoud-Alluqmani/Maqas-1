<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ProductCategory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name_ar',
        'name_en',
        'icon'
    ];


    public function stores(){
        return $this->hasMany(Store::class);
    }

    public function Features(){
        return $this->hasMany(Feature::class);
    }
}
