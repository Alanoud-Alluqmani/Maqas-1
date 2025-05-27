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
        'status_en',
        'service_id_1',
        'service_id_2',
    ];

    protected $casts = [
    'service_id_1' => 'boolean',
    'service_id_2' => 'boolean',
];


    public function orders(){
        return $this->hasMany(Order::class);
    }
}
