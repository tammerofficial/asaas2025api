<?php

namespace App\Http\Requests\Api\Tenant\Unit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191', Rule::unique('units', 'name')],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Unit name is required',
            'name.max' => 'Unit name must not exceed 191 characters',
            'name.unique' => 'This unit already exists',
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

