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
    public function workArea(): JsonResponse
    {
        $locale = App::getLocale();

        $workAreas = WorkArea::with('children')
            ->whereNull('parent_id')
            ->orderBy('id')
            ->get()
            ->map(fn ($item) => [
                'id'       => $item->id,
                'name'     => $item->getTranslation('name', $locale),
                'children' => $item->children->map(fn ($child) => [
                    'id'   => $child->id,
                    'name' => $child->getTranslation('name', $locale),
                ]),
            ]);

        return response()->json($workAreas);
    }

    public function workType(): JsonResponse
    {
        $locale = App::getLocale();

        return response()->json(
            WorkType::all()->map(fn ($item) => [
                'id'   => $item->id,
                'name' => $item->getTranslation('name', $locale),
            ])
        );
    }

    public function currencies(): JsonResponse
    {
        $locale = App::getLocale();

        return response()->json(
            Currency::all()->map(fn ($item) => [
                'id'   => $item->id,
                'name' => $item->getTranslation('name', $locale),
                'icon' => url('/storage/' . $item->icon),
            ])
        );
    }

    public function salaryType(): JsonResponse
    {
        $locale = App::getLocale();

        return response()->json(
            SalaryType::all()->map(fn ($item) => [
                'id'   => $item->id,
                'name' => $item->getTranslation('name', $locale),
            ])
        );
    }

    public function workExpectation(): JsonResponse
    {
        $locale = App::getLocale();

        return response()->json(
            WorkExpectation::all()->map(fn ($item) => [
                'id'   => $item->id,
                'name' => $item->getTranslation('name', $locale),
            ])
        );
    }

    public function citizenship(): JsonResponse
    {
        $locale = App::getLocale();

        return response()->json(
            Citizenship::all()->map(fn ($item) => [
                'id'   => $item->id,
                'name' => $item->getTranslation('name', $locale),
            ])
        );
    }

    public function maritalStatus(): JsonResponse
    {
        $locale = App::getLocale();

        return response()->json(
            MaritalStatus::all()->map(fn ($item) => [
                'id'   => $item->id,
                'name' => $item->getTranslation('name', $locale),
            ])
        );
    }

    public function militaryStatus(): JsonResponse
    {
        $locale = App::getLocale();

        return response()->json(
            MilitaryStatus::all()->map(fn ($item) => [
                'id'   => $item->id,
                'name' => $item->getTranslation('name', $locale),
            ])
        );
    }

    public function driverLicense(): JsonResponse
    {
        $locale = App::getLocale();

        return response()->json(
            DriverLicense::all()->map(fn ($item) => [
                'id'   => $item->id,
                'name' => $item->getTranslation('name', $locale),
            ])
        );
    }

    public function carModels(): JsonResponse
    {
        $locale = App::getLocale();

        return response()->json(
            CarModel::all()->map(fn ($item) => [
                'id'   => $item->id,
                'name' => $item->getTranslation('name', $locale),
            ])
        );
    }

    public function languages(): JsonResponse
    {
        $locale = App::getLocale();

        return response()->json(
            Language::all()->map(fn ($item) => [
                'id'   => $item->id,
                'name' => $item->getTranslation('name', $locale),
            ])
        );
    }

    public function educationType(): JsonResponse
    {
        $locale = App::getLocale();

        return response()->json(
            EducationType::all()->map(fn ($item) => [
                'id'   => $item->id,
                'name' => $item->getTranslation('name', $locale),
            ])
        );
    }

    public function hobbies(): JsonResponse
    {
        $locale = App::getLocale();

        return response()->json(
            Hobby::all()->map(fn ($item) => [
                'id'   => $item->id,
                'name' => $item->getTranslation('name', $locale),
            ])
        );
    }

    public function hardSkills(): JsonResponse
    {
        $locale = App::getLocale();

        return response()->json(
            HardSkill::all()->map(fn ($item) => [
                'id'   => $item->id,
                'name' => $item->getTranslation('name', $locale),
            ])
        );
    }

    public function softSkills(): JsonResponse
    {
        $locale = App::getLocale();

        return response()->json(
            SoftSkill::all()->map(fn ($item) => [
                'id'   => $item->id,
                'name' => $item->getTranslation('name', $locale),
            ])
        );
    }
}
