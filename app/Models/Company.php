<?php

namespace App\Models;

use App\Enum\Company\CompanyStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function workArea(): BelongsTo
    {
        return $this->belongsTo(WorkArea::class, 'work_area_id', 'id');
    }

    public function missions(): HasMany
    {
        return $this->hasMany(CompanyMission::class);
    }

    public function whyChooseUs(): HasMany
    {
        return $this->hasMany(CompanyWhyChooseUs::class);
    }

    public function companyType(): BelongsTo
    {
        return $this->belongsTo(CompanyType::class);
    }
}
