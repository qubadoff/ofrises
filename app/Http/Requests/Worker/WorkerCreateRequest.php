<?php

namespace App\Http\Requests\Worker;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class WorkerCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'location'         => 'required|string|max:255',
            'latitude'         => 'required|string',
            'longitude'        => 'required|string',

            'salary_min'       => 'required|numeric|min:0',
            'salary_max'       => 'required|numeric|min:0|gte:salary_min',
            'currency_id'      => 'required|integer|exists:currencies,id',
            'salary_type_id'   => 'required|integer|exists:salary_types,id',

            'work_area_ids'        => 'required|array|min:1',
            'work_area_ids.*'      => 'integer|distinct|exists:work_areas,id',

            'work_type_id'         => 'required|array|min:1',
            'work_type_id.*'       => 'integer|distinct|exists:work_types,id',

            'work_expectation_id'  => 'nullable|array',
            'work_expectation_id.*'=> 'integer|distinct|exists:work_expectations,id',

            'citizenship_id'       => 'nullable|array',
            'citizenship_id.*'     => 'integer|distinct|exists:citizenships,id',

            'driver_license_id'    => 'nullable|array',
            'driver_license_id.*'  => 'integer|distinct|exists:driver_licenses,id',

            'car_model_id'         => 'nullable|array',
            'car_model_id.*'       => 'integer|distinct|exists:car_models,id',

            'hobby_id'             => 'nullable|array',
            'hobby_id.*'           => 'integer|distinct|exists:hobbies,id',

            'hard_skill_id'        => 'nullable|array',
            'hard_skill_id.*'      => 'integer|distinct|exists:hard_skills,id',

            'soft_skill_id'        => 'nullable|array',
            'soft_skill_id.*'      => 'integer|distinct|exists:soft_skills,id',

            'marital_status_id'    => 'nullable|integer|exists:marital_statuses,id',
            'military_status_id'   => 'nullable|integer|exists:military_statuses,id',

            'birth_place'          => 'nullable|string|max:255',
            'height'               => 'nullable|numeric|min:0|max:300',
            'weight'               => 'nullable|numeric|min:0|max:500',
            'have_a_child'         => 'nullable|boolean',
            'description'          => 'nullable|string|max:5000',

            'educations' => 'nullable|array',
            'educations.*.education_type'  => 'required|integer|in:0,1,2,3,4',
            'educations.*.university_name' => 'required|string|max:255',
            'educations.*.start_date'      => 'required|date',
            'educations.*.end_date'        => 'nullable|date',
            'educations.*.is_present'      => 'boolean',
            'educations.*.description'     => 'nullable|string|max:2000',

            'languages' => 'nullable|array',
            'languages.*.language_id' => 'required|integer|exists:languages,id',
            'languages.*.language_level_id' => 'required|integer|exists:language_levels,id',


        ];
    }
}
