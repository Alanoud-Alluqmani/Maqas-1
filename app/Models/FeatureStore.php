<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class FeatureStore extends Pivot
{
    use SoftDeletes, HasFactory;
   

    public function designs(){
        return $this->hasMany(Design::class , 'feature_store_id');
    }
}
