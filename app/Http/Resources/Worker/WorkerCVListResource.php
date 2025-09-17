<?php

namespace App\Http\Resources\Worker;

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
            'work_types' => WorkType::query()->whereIn('id', (array) $this->work_type_id)
                ->get()
                ->map(fn ($type) => [
                    'id'   => $type->id,
                    'name' => $type->name,
                ]),
        ];
    }
}
