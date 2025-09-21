<?php

namespace App\Http\Requests\Company;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CompanyCreateRequest extends FormRequest
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
            'company_type_id' => ['required', 'integer', 'exists:company_types,id'],
            'name'           => ['required', 'string', 'max:255'],
            'work_area_id'   => ['required', 'integer', 'exists:work_areas,id'],
            'created_date'   => ['required', 'date'],
            'location'       => ['required', 'string', 'max:255'],
            'latitude'       => ['required', 'string', 'max:50'],
            'longitude'      => ['required', 'string', 'max:50'],
            'phone'          => ['required', 'string', 'max:50'],
            'email'          => ['required', 'email', 'max:255'],
            'employee_count' => ['nullable', 'integer', 'min:0'],

            'missions'       => ['nullable', 'array'],
            'missions.*.name'        => ['nullable', 'string', 'max:255'],
            'missions.*.description' => ['nullable', 'string'],

            'why_choose_us' => ['nullable', 'array'],
            'why_choose_us.*.name' => ['nullable', 'string', 'max:255'],
            'why_choose_us.*.description' => ['nullable', 'string'],
        ];
    }
}
