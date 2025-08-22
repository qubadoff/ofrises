<?php

namespace App\Http\Controllers\api\Worker;

use App\Enum\Worker\WorkerStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Worker\WorkerCreateRequest;
use App\Models\Worker;
use App\Models\WorkerPhotoAndVideo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'worker_id' => 'required|integer|exists:workers,id',
            'photos'    => 'nullable',
            'photos.*'  => 'image|max:8192', // 8MB
            'video'     => 'nullable|mimetypes:video/mp4,video/quicktime,video/x-matroska|max:204800', // 200MB
            'append'    => 'sometimes|boolean',
        ]);

        $worker = Worker::query()->findOrFail($request->input('worker_id'));
        $append = $request->boolean('append', true);

        return DB::transaction(function () use ($request, $customer, $worker, $append) {
            $record = WorkerPhotoAndVideo::query()->firstOrNew([
                'customer_id' => $customer->id,
                'worker_id'   => $worker->id,
            ]);

            $existingPhotos = is_string($record->photos) ? json_decode($record->photos, true) : [];
            if (!is_array($existingPhotos)) {
                $existingPhotos = [];
            }
            $existingVideo = $record->video;

            $newPhotoPaths = [];
            $photoFiles = $request->file('photos');
            if ($photoFiles instanceof UploadedFile) {
                $photoFiles = [$photoFiles];
            }

            if (is_array($photoFiles)) {
                foreach ($photoFiles as $file) {
                    if ($file instanceof UploadedFile && $file->isValid()) {
                        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                        $path = "workers/photos/{$filename}";
                        $file->storeAs('workers/photos', $filename, 'public');
                        $newPhotoPaths[] = $path;
                    }
                }
            }

            $finalPhotos = $append
                ? array_values(array_unique(array_merge($existingPhotos, $newPhotoPaths)))
                : ($newPhotoPaths ?: []);

            // --- Video
            $finalVideo = $existingVideo;
            $videoFile = $request->file('video');
            if ($videoFile instanceof UploadedFile && $videoFile->isValid()) {
                $filename = uniqid() . '.' . $videoFile->getClientOriginalExtension();
                $path = "workers/videos/{$filename}";
                $videoFile->storeAs('workers/videos', $filename, 'public');
                $finalVideo = $path;
            }

            $record->photos = json_encode($finalPhotos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $record->video  = $finalVideo;
            $record->save();

            // --- Tam URL üret
            $photoUrls = array_map(fn ($p) => url(Storage::disk('public')->url($p)), $finalPhotos);
            $videoUrl  = $finalVideo ? url(Storage::disk('public')->url($finalVideo)) : null;

            return response()->json([
                'message' => 'The image and videos was uploaded successfully.',
                'data'    => [
                    'id'          => $record->id,
                    'customer_id' => $record->customer_id,
                    'worker_id'   => (string) $record->worker_id,
                    'photos'      => $photoUrls,
                    'video'       => $videoUrl,
                ],
            ]);
        });
    }


}
