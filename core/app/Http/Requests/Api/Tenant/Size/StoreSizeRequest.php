<?php

namespace App\Http\Requests\Api\Tenant\Size;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreSizeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'size_code' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', Rule::unique('sizes', 'slug')],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Size name is required',
            'name.max' => 'Size name must not exceed 191 characters',
            'size_code.required' => 'Size code is required',
            'size_code.max' => 'Size code must not exceed 191 characters',
            'slug.unique' => 'This slug is already taken',
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

