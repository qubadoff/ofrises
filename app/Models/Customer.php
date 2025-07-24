<?php

namespace App\Models;

use App\Enum\Customer\CustomerStatusEnum;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use SoftDeletes, HasApiTokens;

    protected $table = 'customers';

    protected $guarded = ['id'];

    protected $casts = [
        'status' => CustomerStatusEnum::class,
    ];
}
