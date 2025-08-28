<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Spatie\Translatable\HasTranslations;

class CarModel extends Model
{
    use HasTranslations;

    protected $table = 'car_models';

    protected $guarded = ['id'];

    public array $translatable = ['name'];

    public function getNameAttribute($value)
    {
        return $this->getTranslation('name', App::getLocale());
    }
}
