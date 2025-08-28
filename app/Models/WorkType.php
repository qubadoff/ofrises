<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class WorkType extends Model
{
    use HasTranslations;

    protected $table = 'work_types';

    protected $guarded = ['id'];

    public array $translatable = ['name'];
}
