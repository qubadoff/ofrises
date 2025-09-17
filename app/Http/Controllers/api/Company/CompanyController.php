<?php

namespace App\Http\Controllers\api\Company;

use App\Enum\Company\CompanyStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CompanyCreateRequest;
use App\Http\Resources\Company\CompanyResource;
use App\Models\Company;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function create(CompanyCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $company = new Company();
            $company->customer_id    = Auth::user()->id;
            $company->name           = $request->input('name');
            $company->work_area_id   = (int) $request->input('work_area_id');
            $company->created_date   = $request->input('created_date');
            $company->location       = $request->input('location');
            $company->latitude       = $request->input('latitude');
            $company->longitude      = $request->input('longitude');
            $company->phone          = $request->input('phone');
            $company->email          = $request->input('email');
            $company->employee_count = (int) $request->input('employee_count', 0);

            if ($request->hasFile('profile_photo')) {
                $company->profile_photo = $request->file('profile_photo')
                    ->store('companies/profile_photos', 'public');
            }

            $mediaFiles = [];
            if ($request->hasFile('media')) {
                foreach ($request->file('media') as $file) {
                    $mediaFiles[] = $file->store('companies/media', 'public');
                }
            }
            $company->media = $mediaFiles;

            $company->status = CompanyStatusEnum::PENDING->value;

            $company->save();

            if ($request->has('missions') && is_array($request->missions)) {
                foreach ($request->missions as $mission) {
                    $company->missions()->create([
                        'name' => $mission['name'],
                        'description' => $mission['description'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Company created successfully',
                'company'    => new CompanyResource($company),
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
