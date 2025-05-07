<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreLocation extends Model
{
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
