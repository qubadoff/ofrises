<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Worker extends Model
{
    protected $table = 'workers';

    protected $guarded = ['id'];

    public function workAreas(): BelongsToMany
    {
        return $this->belongsToMany(
            WorkArea::class,      // <-- pivot diğer ucu WorkArea
            'worker_work_areas',  // pivot tablo adı
            'worker_id',          // bu modelin FK'sı
            'work_area_id'        // karşı FK
        )
            ->withPivot('customer_id')
            ->withTimestamps();
    }
}
