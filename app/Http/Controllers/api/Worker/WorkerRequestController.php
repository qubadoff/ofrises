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
