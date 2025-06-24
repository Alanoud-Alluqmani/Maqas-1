<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use SoftDeletes, HasFactory;
    protected $hidden = ['customer_id', 'store_id', 'service_id', 'status_id',
     'customer_location_id', 'store_location_id'];

     
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

    public function service(){
    return $this->belongsTo(Service::class);
    }

}
