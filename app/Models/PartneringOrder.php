<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartneringOrder extends Model
{
    use SoftDeletes, HasFactory;
    public function stores(){
        return $this->belongsTo(Store::class);
    }
}
