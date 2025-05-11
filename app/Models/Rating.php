<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'rating',
        'comment'
    ];


    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function user(){
        return $this->hasOneThrough(User::class, Order::class);
    }

    public function store(){
        return $this->hasOneThrough(Store::class, Order::class);
    }
}
