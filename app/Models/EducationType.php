<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class EducationType extends Model
{
    use HasTranslations;

    protected $table = 'education_types';

    protected $guarded = ['id'];

    public array $translatable = ['name'];
}
