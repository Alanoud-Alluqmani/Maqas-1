<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;


class FeatureStore extends Pivot
{
    use SoftDeletes;
    public function designs(){
        return $this->hasMany(Design::class);
    }
}
