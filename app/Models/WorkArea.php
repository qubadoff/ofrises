<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkArea extends Model
{
    protected $table = 'work_areas';
    protected $guarded = ['id'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(WorkArea::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(WorkArea::class, 'parent_id')->with('children');
    }

    public function workers(): BelongsToMany
    {
        return $this->belongsToMany(
            Worker::class,
            'worker_work_areas',
            'work_area_id',
            'worker_id'
        )
            ->withPivot('customer_id')
            ->withTimestamps();
    }

    public function scopeLeaves($q)
    {
        return $q->whereDoesntHave('children');
    }
}
