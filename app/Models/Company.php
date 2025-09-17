<?php

namespace App\Models;

use App\Enum\Company\CompanyStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $table = 'companies';

    protected $guarded = ['id'];

    protected $casts = [
        'status' => CompanyStatusEnum::class,
        'media' => 'array',
    ];

    public function workArea(): BelongsTo
    {
        return $this->belongsTo(WorkArea::class, 'work_area_id', 'id');
    }
}
