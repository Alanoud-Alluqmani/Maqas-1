<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = [
        'status_ar',
        'status_en'
    ];

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
