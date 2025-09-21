<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class CompanyType extends Model
{
    use SoftDeletes, HasTranslations;

    protected $table = 'company_types';

    protected $guarded = ['id'];

    public array $translatable = ['name'];

    protected $casts = [
        'name' => 'array',
    ];
}
