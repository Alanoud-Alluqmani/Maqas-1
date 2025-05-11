<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function measure(){
        return $this->belongsTo(Measure::class);
    }


    public function designs(){
        return $this->belongsToMany(Design::class);
    }
}
