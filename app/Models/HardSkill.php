<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HardSkill extends Model
{
    use HasTranslations;

    protected $table = 'hard_skills';

    protected $guarded = ['id'];

    public array $translatable = ['name'];
}
