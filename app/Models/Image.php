<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'image',
    ];

    public function design(){
        return $this->belongsTo(Design::class);
    }
}
