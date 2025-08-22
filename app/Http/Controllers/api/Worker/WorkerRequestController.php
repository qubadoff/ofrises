<?php

namespace App\Http\Controllers\api\Worker;

use App\Enum\Worker\WorkerStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Worker\WorkerCreateRequest;
use App\Models\Worker;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkerRequestController extends Controller
{
    public function __construct(){}

    public function create(WorkerCreateRequest $request): JsonResponse
    {
        $customer = Auth::user();

        $data = $request->validated();

        return DB::transaction(function () use ($data, $customer) {
            $worker = Worker::query()->create([
                'customer_id'        => $customer->id,

                'location'           => $data['location'],
                'latitude'           => $data['latitude'],
                'longitude'          => $data['longitude'],

                'salary_min'         => $data['salary_min'],
                'salary_max'         => $data['salary_max'],
                'currency_id'        => $data['currency_id'],
                'salary_type_id'     => $data['salary_type_id'],

                'work_type_id'       => $data['work_type_id'],
                'work_expectation_id'=> $data['work_expectation_id'] ?? [],
                'citizenship_id'     => $data['citizenship_id'] ?? [],
                'driver_license_id'  => $data['driver_license_id'] ?? [],
                'car_model_id'       => $data['car_model_id'] ?? [],
                'hobby_id'           => $data['hobby_id'] ?? [],
                'hard_skill_id'      => $data['hard_skill_id'] ?? [],
                'soft_skill_id'      => $data['soft_skill_id'] ?? [],

                'birth_place'        => $data['birth_place'] ?? null,
                'marital_status_id'  => $data['marital_status_id'] ?? null,
                'height'             => $data['height'] ?? null,
                'weight'             => $data['weight'] ?? null,
                'military_status_id' => $data['military_status_id'] ?? null,
                'have_a_child'       => $data['have_a_child'] ?? null,
                'description'        => $data['description'] ?? null,

                'status' => WorkerStatusEnum::PENDING->value,
            ]);

            if (!empty($data['work_area_ids'])) {
                $payload = collect($data['work_area_ids'])
                    ->filter()
                    ->unique()
                    ->mapWithKeys(fn ($workAreaId) => [
                        $workAreaId => ['customer_id' => $customer->id],
                    ])->all();

                $worker->workAreas()->attach($payload);
            }

            if (!empty($data['educations']) && is_array($data['educations'])) {
                $rows = collect($data['educations'])
                    ->filter(fn ($row) => !empty($row['education_type']) && !empty($row['university_name']) && !empty($row['start_date']))
                    ->map(function ($row) use ($customer) {
                        $isPresent = (bool) ($row['is_present'] ?? false);
                        return [
                            'customer_id'     => $customer->id,
                            'education_type'  => (int) $row['education_type'],
                            'university_name' => $row['university_name'],
                            'start_date'      => $row['start_date'],
                            'end_date'        => $isPresent ? null : ($row['end_date'] ?? null),
                            'is_present'      => $isPresent,
                            'description'     => $row['description'] ?? null,
                        ];
                    })->values()->all();

                if (!empty($rows)) {
                    $worker->educations()->createMany($rows);
                }
            }

            if (!empty($data['languages']) && is_array($data['languages'])) {
                $rows = collect($data['languages'])
                    ->filter(fn ($row) => !empty($row['language_id']) && !empty($row['language_level_id']))
                    ->map(function ($row) use ($customer) {
                        return [
                            'customer_id'       => $customer->id,
                            'language_id'       => (int) $row['language_id'],
                            'language_level_id' => (int) $row['language_level_id'],
                        ];
                    })->values()->all();

                if (!empty($rows)) {
                    $worker->languages()->createMany($rows);
                }
            }


            return response()->json([
                'message' => 'Worker created successfully.',
                'data'    => [
                    'id'             => $worker->id,
                    'customer_id'    => $worker->customer_id,
                    'work_area_ids'  => $data['work_area_ids'] ?? [],
                ],
            ], 201);
        });
    }
}
