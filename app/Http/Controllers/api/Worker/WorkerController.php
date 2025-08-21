<?php

namespace App\Http\Controllers\api\Worker;

use App\Http\Controllers\Controller;
use App\Models\CarModel;
use App\Models\Citizenship;
use App\Models\Currency;
use App\Models\DriverLicense;
use App\Models\MaritalStatus;
use App\Models\MilitaryStatus;
use App\Models\SalaryType;
use App\Models\WorkArea;
use App\Models\WorkExpectation;
use App\Models\WorkType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function workArea(): JsonResponse
    {
        return response()->json(WorkArea::with('children')
            ->whereNull('parent_id')
            ->get());
    }

    public function workType(): JsonResponse
    {
        return response()->json(WorkType::all());
    }

    public function currencies(): JsonResponse
    {
        $currency = Currency::query()->select('id', 'name', 'icon')->first();

        return response()->json([
            'id'   => $currency->id,
            'name' => $currency->name,
            'icon' => url('/') . '/storage/' . $currency->icon,
        ]);
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
        return response()->json(CarModel::all());
    }
}
