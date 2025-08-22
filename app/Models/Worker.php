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
            WorkArea::class,
            'worker_work_areas',
            'worker_id',
            'work_area_id'
        )
            ->withPivot('customer_id')
            ->withTimestamps();
    }

    public function workAreasForCustomer(?int $customerId = null): BelongsToMany
    {
        $customerId ??= auth()->user()?->customer_id;
        return $this->workAreas()->wherePivot('customer_id', $customerId);
    }
}
