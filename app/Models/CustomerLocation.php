<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerLocation extends Model
{
    use SoftDeletes;
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
