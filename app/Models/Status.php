<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'status_ar',
        'status_en'
    ];

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
