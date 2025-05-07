<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function customer_location(){
        return $this->belongsTo(CustomerLocation::class);
    }

    public function store(){
        return $this->belongsTo(Store::class);
    }


    public function store_location(){
        return $this->belongsTo(StoreLocation::class);
    }

    public function items(){
        return $this->hasMany(Item::class);
    }

    public function rating(){
        return $this->hasOne(Rating::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }
}
