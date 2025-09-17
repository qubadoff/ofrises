<?php

namespace App\Http\Resources\Company;

use App\Enum\Company\CompanyStatusEnum;
use App\Filament\Resources\WorkAreaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'name' => $this->name,
            'work_area' => [
                'id' => $this->work_area_id,
                'name' => $this->workArea->name,
            ],
            'created_date' => $this->created_date,
            'location' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'phone' => $this->phone,
            'email' => $this->email,
            'employee_count' => $this->employee_count,
            'profile_photo' => $this->profile_photo
                ? url("storage/" . $this->profile_photo)
                : null,
            'media' => $this->media
                ? collect($this->media)->map(fn ($media) => url("storage/" . $media))
                : [],
            'status' => [
                'id' => $this->status,
                'name' => $this->status instanceof CompanyStatusEnum
                    ? $this->status->getLabel()
                    : $this->status,
            ]
        ];
    }
}
