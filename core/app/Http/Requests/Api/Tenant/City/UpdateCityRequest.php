<?php

namespace App\Http\Requests\Api\Tenant\City;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateCityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $cityId = $this->route('city')->id ?? null;
        
        return [
            'name' => ['sometimes', 'required', 'string', 'max:191', Rule::unique('cities', 'name')->ignore($cityId)],
            'country_id' => ['sometimes', 'required', 'integer', 'exists:countries,id'],
            'state_id' => ['sometimes', 'required', 'integer', 'exists:states,id'],
            'status' => ['nullable', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'City name is required',
            'name.max' => 'City name must not exceed 191 characters',
            'name.unique' => 'This city already exists',
            'country_id.required' => 'Country is required',
            'country_id.exists' => 'Selected country does not exist',
            'state_id.required' => 'State is required',
            'state_id.exists' => 'Selected state does not exist',
            'status.in' => 'Status must be either 0 (inactive) or 1 (active)',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}

