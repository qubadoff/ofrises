<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Worker extends Model
{
    protected $table = 'workers';

    protected $guarded = ['id'];

    public function workAreas(): BelongsToMany
    {
        return $this->belongsToMany(
            WorkArea::class,
            'worker_work_areas',
            'worker_id',
            'work_area_id'
        )
            ->withPivot('customer_id')
            ->withTimestamps();
    }

    public function workType(): BelongsTo
    {
        return $this->belongsTo(WorkType::class, 'work_type_id');
    }
}
