<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreLocation extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'loc_url',
        'store_id',
        'latitude',
        'longitude',

    ];

     protected $hidden = [ 'store_id'];

    public function store(){
        return $this->belongsTo(Store::class);
    }


    public function orders(){
        return $this->hasMany(Order::class);
    }
}
