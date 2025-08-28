<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Hobby extends Model
{
    use HasTranslations;

    protected $table = 'hobbies';

    protected $guarded = ['id'];

    public array $translatable = ['name'];

    protected $casts = [
        'name' => 'array',
    ];
}
