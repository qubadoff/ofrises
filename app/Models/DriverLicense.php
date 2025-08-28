<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class DriverLicense extends Model
{
    use HasTranslations;

    protected $table = 'driver_licenses';

    protected $guarded = ['id'];

    public array $translatable = ['name'];

    protected $casts = [
        'name' => 'array',
    ];
}
