<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SalaryType extends Model
{
    use HasTranslations;

    protected $table = 'salary_types';

    protected $guarded = ['id'];

    public array $translatable = ['name'];

    protected $casts = [
        'name' => 'array',
    ];
}
