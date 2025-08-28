<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Citizenship extends Model
{
    use HasTranslations;

    public array $translatable = ['name'];

    protected $table = 'citizenships';

    protected $guarded = ['id'];

    protected $casts = [
        'name' => 'array',
    ];
}
