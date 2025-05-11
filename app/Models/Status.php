<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'status_ar',
        'status_en'
    ];

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
