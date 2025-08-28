<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SoftSkill extends Model
{
    use HasTranslations;

    protected $table = 'soft_skills';

    protected $guarded = ['id'];

    public array $translatable = ['name'];
}
