<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'rating',
        'comment'
    ];


    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function custumer(){
        return $this->hasOneThrough(Customer::class, Order::class);
    }

    public function store(){
        return $this->hasOneThrough(Store::class, Order::class);
    }
}
