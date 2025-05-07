<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureStore extends Model
{
    public function designs(){
        return $this->hasMany(Design::class);
    }
}
