<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerLocation extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'loc_url',
        'customer_id',
        'latitude',
        'longitude',
        
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
