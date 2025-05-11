<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerLocation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name_ar',
        'name_en',
        'phone',
        'password',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function locations(){
        return $this->hasMany(CustomerLocation::class);
    }

    public function measures(){
        return $this->hasMany(Measure::class);
    } 

    public function orders(){
        return $this->hasMany(Order::class);
    }


   
}
