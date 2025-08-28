<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class WorkExpectation extends Model
{
    use HasTranslations;

    protected $table = 'work_expectations';

    protected $guarded = ['id'];

    public array $translatable = ['name'];

    protected $casts = [
        'name' => 'array',
    ];
}
