<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;


class ServiceStore extends Pivot
{
    /** @use HasFactory<\Database\Factories\ServiceStoreFactory> */
    use SoftDeletes, HasFactory;
}
