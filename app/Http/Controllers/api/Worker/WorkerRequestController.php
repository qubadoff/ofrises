<?php

namespace App\Http\Controllers\api\Worker;

use App\Enum\Worker\WorkerStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Worker\WorkerCreateRequest;
use App\Models\Worker;
use App\Models\WorkerPhotoAndVideo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class WorkerRequestController extends Controller
{
    public function __construct(){}

    public function create(WorkerCreateRequest $request): JsonResponse
    {
        $customer = Auth::user();

        $data = $request->validated();

        $existingWorker = Worker::query()->where('customer_id', $customer->id)->first();

        if ($existingWorker) {
            return response()->json([
                'message' => 'This customer has already been requested.',
                'worker_id' => $existingWorker->id,
            ], 400);
        }

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

            if (!empty($data['workExperience']) && is_array($data['workExperience'])) {
                $rows = collect($data['workExperience'])
                    ->filter(fn ($row) => !empty($row['company_name']) && !empty($row['position_name']) && !empty($row['start_date']))
                    ->map(function ($row) use ($customer) {
                        $isPresent = (bool) ($row['is_present'] ?? false);
                        return [
                            'customer_id'   => $customer->id,
                            'company_name'  => $row['company_name'],
                            'position_name' => $row['position_name'],
                            'start_date'    => $row['start_date'],
                            'end_date'      => $isPresent ? null : ($row['end_date'] ?? null),
                            'is_present'    => $isPresent,
                            'workload'      => $row['workload'] ?? null,
                            'description'   => $row['description'] ?? null,
                        ];
                    })->values()->all();

                if (!empty($rows)) {
                    $worker->workExperience()->createMany($rows);
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

    public function uploadPhotoAndVideo(Request $request): JsonResponse
    {
        $customer = Auth::user();

        $request->validate([
            'worker_id' => 'required|exists:workers,id',
            'photos.*'  => 'nullable|image|max:5120',
            'video'     => 'nullable|mimes:mp4,mov,avi,webm|max:51200',
        ]);

        $workerId = $request->input('worker_id');

        if (WorkerPhotoAndVideo::query()->where('customer_id', $customer->id)->where('worker_id', $workerId)->exists()) {
            return response()->json([
                'message' => 'This worker already has a image and video.',
            ], 400);
        }

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store("worker/{$workerId}/photos", 'public');
                $photos[] = $path;
            }
        }

        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store("worker/{$workerId}/videos", 'public');
        }

        $record = WorkerPhotoAndVideo::query()->create([
            'customer_id' => $customer->id,
            'worker_id'   => $workerId,
            'photos'      => json_encode($photos),
            'video'       => $videoPath,
        ]);

        $photoUrls = collect($photos)->map(fn($p) => Storage::url($p))->all();
        $videoUrl  = $videoPath ? Storage::url($videoPath) : null;

        return response()->json([
            'message' => 'The image and videos was uploaded successfully.',
            'data'    => [
                'id'          => $record->id,
                'customer_id' => $record->customer_id,
                'worker_id'   => $record->worker_id,
                'photos'      => $photoUrls,
                'video'       => $videoUrl,
            ],
        ]);
    }

}
