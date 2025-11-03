<?php

namespace App\Http\Requests\Api\Tenant\ProductAttribute;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProductAttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'terms' => ['required', 'array', 'min:1'],
            'terms.*' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Attribute title is required',
            'title.max' => 'Attribute title must not exceed 255 characters',
            'terms.required' => 'Terms are required',
            'terms.array' => 'Terms must be an array',
            'terms.min' => 'At least one term is required',
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

