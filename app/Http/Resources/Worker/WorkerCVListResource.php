<?php

namespace App\Http\Resources\Worker;

use App\Enum\Worker\WorkerStatusEnum;
use App\Models\CarModel;
use App\Models\DriverLicense;
use App\Models\Hobby;
use App\Models\WorkType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerCVListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->customer->name,
            'surname' => $this->customer->surname,
            'email' => $this->customer->email,
            'phone' => $this->customer->phone,
            'location' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'birth_place' => $this->birth_place,
            'height' => $this->height,
            'weight' => $this->weight,
            'description' => $this->description,
            'have_a_child' => (bool) $this->have_a_child,
            'currency' => [
                'id' => $this->currency->id,
                'name' => $this->currency->name,
            ],
            'salary_type' => [
                'id' => $this->salaryType->id,
                'name' => $this->salaryType->name,
            ],
            'work_expectation' => [
                'id' => $this->workExpectation->id,
                'name' => $this->workExpectation->name,
            ],
            'citizenship' => [
                'id' => $this->citizenship->id,
                'name' => $this->citizenship->name,
            ],
            'marital_status' => [
                'id' => $this->maritalStatus->id,
                'name' => $this->maritalStatus->name,
            ],
            'military_status' => [
                'id' => $this->militaryStatus->id,
                'name' => $this->militaryStatus->name,
            ],
            'work_types' => WorkType::query()->whereIn('id', (array) $this->work_type_id)
                ->get()
                ->map(fn ($type) => [
                    'id'   => $type->id,
                    'name' => $type->name,
                ]),
            'driver_license' => DriverLicense::query()->whereIn('id', (array) $this->driver_license_id)
                ->get()
                ->map(fn ($type) => [
                    'id'   => $type->id,
                    'name' => $type->name,
                ]),
            'car_model_id' => CarModel::query()->whereIn('id', (array) $this->car_model_id)
                ->get()
                ->map(fn ($type) => [
                    'id'   => $type->id,
                    'name' => $type->name,
                ]),
            'hobby_id' => Hobby::query()->whereIn('id', (array) $this->hobby_id)
                ->get()
                ->map(fn ($type) => [
                    'id'   => $type->id,
                    'name' => $type->name,
                ]),
            'status' => $this->status->getLable(),
        ];
    }
}
