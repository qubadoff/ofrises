<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Language extends Model
{
    use HasTranslations;

    protected $table = 'languages';

    protected $guarded = ['id'];

    public array $translatable = ['name'];
}
