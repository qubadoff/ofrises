<?php

namespace App\Http\Controllers\api\Worker;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarModelResource;
use App\Models\CarModel;
use App\Models\Citizenship;
use App\Models\Currency;
use App\Models\DriverLicense;
use App\Models\EducationType;
use App\Models\HardSkill;
use App\Models\Hobby;
use App\Models\Language;
use App\Models\MaritalStatus;
use App\Models\MilitaryStatus;
use App\Models\SalaryType;
use App\Models\SoftSkill;
use App\Models\WorkArea;
use App\Models\WorkExpectation;
use App\Models\WorkType;
use Illuminate\Http\JsonResponse;

class WorkerController extends Controller
{
    public function workArea(): JsonResponse
    {
        $workAreas = WorkArea::with('children')
            ->whereNull('parent_id')
            ->orderBy('id')
            ->get(['id', 'parent_id', 'name']);

        return response()->json($workAreas);
    }

    public function workType(): JsonResponse
    {
        return response()->json(WorkType::all());
    }

    public function currencies(): JsonResponse
    {
        $currencies = Currency::query()
            ->select('id', 'name', 'icon')
            ->get()
            ->map(function ($currency) {
                return [
                    'id'   => $currency->id,
                    'name' => $currency->name,
                    'icon' => url('/storage/' . $currency->icon),
                ];
            });

        return response()->json($currencies);
    }


    public function salaryType(): JsonResponse
    {
        return response()->json(SalaryType::all());
    }

    public function workExpectation(): JsonResponse
    {
        return response()->json(WorkExpectation::all());
    }

    public function citizenship(): JsonResponse
    {
        return response()->json(Citizenship::all());
    }

    public function maritalStatus(): JsonResponse
    {
        return response()->json(MaritalStatus::all());
    }

    public function militaryStatus(): JsonResponse
    {
        return response()->json(MilitaryStatus::all());
    }

    public function driverLicense(): JsonResponse
    {
        return response()->json(DriverLicense::all());
    }

    public function carModels(): JsonResponse
    {
        return response()->json(CarModelResource::collection(CarModel::all()));
    }

    public function languages(): JsonResponse
    {
        return response()->json(Language::all());
    }

    public function educationType(): JsonResponse
    {
        return response()->json(EducationType::all());
    }

    public function hobbies(): JsonResponse
    {
        return response()->json(Hobby::all());
    }

    public function hardSkills(): JsonResponse
    {
        return response()->json(HardSkill::all());
    }

    public function softSkills(): JsonResponse
    {
        return response()->json(SoftSkill::all());
    }
}
