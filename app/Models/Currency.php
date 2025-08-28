<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Currency extends Model
{
    use HasTranslations;

    protected $table = 'currencies';

    protected $guarded = ['id'];

    public array $translatable = ['name'];
}
