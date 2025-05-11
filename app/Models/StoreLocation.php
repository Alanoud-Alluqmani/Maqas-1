<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreLocation extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'loc_url',
    ];

    public function store(){
        return $this->belongsTo(Store::class);
    }


    public function orders(){
        return $this->hasMany(Order::class);
    }
}
