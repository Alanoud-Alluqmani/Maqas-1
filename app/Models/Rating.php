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
}
