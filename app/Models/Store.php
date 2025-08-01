<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{ 
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name_ar',
        'name_en',
        'legal',
        'phone',
        'email',
        'product_category_id'
    ];

    public function partnering_order(){
        return $this->hasOne(PartneringOrder::class);
    }

    public function services(){
        return $this->belongsToMany(Service::class);
    }

    public function features(){
        return $this->belongsToMany(Feature::class)
         ->withPivot('id');
    }

    public function product_category(){
        return $this->belongsTo(ProductCategory::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function locations(){
        return $this->hasMany(StoreLocation::class);
    }

    public function users(){
        return $this->hasMany(User::class);
    }

    public function ratings(){
        return $this->hasManyThrough(Rating::class, through: Order::class);
    }
}
