<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartneringOrder extends Model
{
    public function stores(){
        return $this->belongsTo(Store::class);
    }
}
