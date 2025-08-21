<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}
