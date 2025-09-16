<?php

namespace App\Http\Controllers\api\Worker;

use App\Http\Controllers\Controller;
use App\Models\{
    CarModel,
    Citizenship,
    Currency,
    DriverLicense,
    EducationType,
    HardSkill,
    Hobby,
    Language,
    MaritalStatus,
    MilitaryStatus,
    SalaryType,
    SoftSkill,
    WorkArea,
    WorkExpectation,
    WorkType
};
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

class WorkerController extends Controller
{
    private string $locale;

    public function __construct()
    {
        $this->locale = App::getLocale();
    }

    public function workArea(): JsonResponse
    {
        $workAreas = WorkArea::with('children')
            ->whereNull('parent_id')
            ->orderBy('id')
            ->get()
            ->map(fn ($item) => $this->formatWorkArea($item));

        return response()->json($workAreas);
    }

    private function formatWorkArea($workArea): array
    {
        return [
            'id'       => $workArea->id,
            'name'     => $workArea->getTranslation('name', $this->locale),
            'children' => $workArea->children->map(
                fn ($child) => $this->formatWorkArea($child)
            )->toArray(),
        ];
    }

    public function workType(): JsonResponse
    {
        return response()->json(
            WorkType::all()->map(fn ($item) => $this->formatItem($item))
        );
    }

    public function currencies(): JsonResponse
    {
        return response()->json(
            Currency::all()->map(fn ($item) => [
                'id'   => $item->id,
                'name' => $item->getTranslation('name', $this->locale),
                'icon' => url('/storage/' . $item->icon),
            ])
        );
    }

    public function salaryType(): JsonResponse
    {
        return response()->json(
            SalaryType::all()->map(fn ($item) => $this->formatItem($item))
        );
    }

    public function workExpectation(): JsonResponse
    {
        return response()->json(
            WorkExpectation::all()->map(fn ($item) => $this->formatItem($item))
        );
    }

    public function citizenship(): JsonResponse
    {
        return response()->json(
            Citizenship::all()->map(fn ($item) => $this->formatItem($item))
        );
    }

    public function maritalStatus(): JsonResponse
    {
        return response()->json(
            MaritalStatus::all()->map(fn ($item) => $this->formatItem($item))
        );
    }

    public function militaryStatus(): JsonResponse
    {
        return response()->json(
            MilitaryStatus::all()->map(fn ($item) => $this->formatItem($item))
        );
    }

    public function driverLicense(): JsonResponse
    {
        return response()->json(
            DriverLicense::all()->map(fn ($item) => $this->formatItem($item))
        );
    }

    public function carModels(): JsonResponse
    {
        return response()->json(
            CarModel::all()->map(fn ($item) => $this->formatItem($item))
        );
    }

    public function languages(): JsonResponse
    {
        return response()->json(
            Language::all()->map(fn ($item) => $this->formatItem($item))
        );
    }

    public function educationType(): JsonResponse
    {
        return response()->json(
            EducationType::all()->map(fn ($item) => $this->formatItem($item))
        );
    }

    public function hobbies(): JsonResponse
    {
        return response()->json(
            Hobby::all()->map(fn ($item) => $this->formatItem($item))
        );
    }

    public function hardSkills(): JsonResponse
    {
        return response()->json(
            HardSkill::all()->map(fn ($item) => $this->formatItem($item))
        );
    }

    public function softSkills(): JsonResponse
    {
        return response()->json(
            SoftSkill::all()->map(fn ($item) => $this->formatItem($item))
        );
    }

    private function formatItem($item): array
    {
        return [
            'id'   => $item->id,
            'name' => $item->getTranslation('name', $this->locale),
        ];
    }
}
