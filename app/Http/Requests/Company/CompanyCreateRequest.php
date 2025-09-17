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
            'customer_id'    => ['required', 'integer', 'exists:customers,id'],
            'name'           => ['required', 'string', 'max:255'],
            'work_area_id'   => ['required', 'integer', 'exists:work_areas,id'],
            'created_date'   => ['required', 'date'],
            'location'       => ['required', 'string', 'max:255'],
            'latitude'       => ['required', 'string', 'max:50'],
            'longitude'      => ['required', 'string', 'max:50'],
            'phone'          => ['required', 'string', 'max:50'],
            'email'          => ['required', 'email', 'max:255'],
            'employee_count' => ['nullable', 'integer', 'min:0'],
            'profile_photo'  => ['required', 'file', 'image', 'mimes:jpeg,png,jpg', 'max:5800'],

            'media'          => ['nullable', 'array'],
            'media.*'        => [
                'file',
                'max:20480', // 20MB limit
                function ($attribute, $value, $fail) {
                    $mime = $value->getMimeType();
                    if (! in_array($mime, [
                        'image/jpeg', 'image/png', 'image/jpg',
                        'video/mp4', 'video/quicktime', 'video/x-msvideo'
                    ])) {
                        $fail($attribute.' sadece jpeg, png, jpg, mp4, mov, avi olabilir.');
                    }
                }
            ],
        ];
    }
}
