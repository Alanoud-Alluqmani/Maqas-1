<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLocation extends Model
{
    protected $fillable = [
        'loc_url',
        
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
