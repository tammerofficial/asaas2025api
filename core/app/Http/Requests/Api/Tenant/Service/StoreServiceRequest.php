<?php

namespace App\Http\Requests\Api\Tenant\Service;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', 'unique:services,slug'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:service_categories,id'],
            'image' => ['nullable', 'string', 'max:191'],
            'meta_tag' => ['nullable', 'string', 'max:191'],
            'meta_description' => ['nullable', 'string', 'max:191'],
            'status' => ['nullable', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Service title is required',
            'title.max' => 'Service title must not exceed 191 characters',
            'description.required' => 'Service description is required',
            'category_id.required' => 'Service category is required',
            'category_id.exists' => 'Selected service category does not exist',
            'slug.unique' => 'This slug is already taken',
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

