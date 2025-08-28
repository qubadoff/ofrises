<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class WorkerWorkArea extends Model
{

    protected $table = 'worker_work_areas';

    protected $guarded = ['id'];

    public $incrementing = false;
    protected $primaryKey = null;
}
