<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartneringOrder extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'status',
        // 'id'
    ];

     protected $hidden = ['store_id'];


    public function store(){
        return $this->belongsTo(Store::class);
    }
}
