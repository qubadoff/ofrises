<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function salaryType(): BelongsTo
    {
        return $this->belongsTo(SalaryType::class, 'salary_type_id');
    }

    public function workExpectation(): BelongsTo
    {
        return $this->belongsTo(WorkExpectation::class, 'work_expectation_id');
    }

    public function citizenship(): BelongsTo
    {
        return $this->belongsTo(Citizenship::class, 'citizenship_id');
    }

    public function maritalStatus(): BelongsTo
    {
        return $this->belongsTo(MaritalStatus::class, 'marital_status_id');
    }

    public function militaryStatus(): BelongsTo
    {
        return $this->belongsTo(MilitaryStatus::class, 'military_status_id');
    }

    public function driverLicense(): BelongsTo
    {
        return $this->belongsTo(DriverLicense::class, 'driver_license_id');
    }

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    public function hobby(): BelongsTo
    {
        return $this->belongsTo(Hobby::class, 'hobby_id');
    }

    public function hardSkill(): BelongsTo
    {
        return $this->belongsTo(HardSkill::class, 'hard_skill_id');
    }

    public function softSkill(): BelongsTo
    {
        return $this->belongsTo(SoftSkill::class, 'soft_skill_id');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(WorkerEducation::class, 'worker_id');
    }

    public function languages(): HasMany
    {
        return $this->hasMany(WorkerLanguage::class, 'worker_id');
    }

    public function workExperience(): HasMany
    {
        return $this->hasMany(WorkerExperience::class, 'worker_id');
    }

    public function photoAndVideo(): HasOne
    {
        return $this->hasOne(WorkerPhotoAndVideo::class, 'worker_id');
    }
}
