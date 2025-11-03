<?php

namespace App\Http\Requests\Api\Tenant\Brand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', Rule::unique('brands', 'slug')],
            'description' => ['nullable', 'string'],
            'title' => ['nullable', 'string', 'max:191'],
            'image_id' => ['nullable', 'integer', 'exists:media_uploaders,id'],
            'banner_id' => ['nullable', 'integer', 'exists:media_uploaders,id'],
            'url' => ['nullable', 'url', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Brand name is required',
            'name.max' => 'Brand name must not exceed 191 characters',
            'slug.unique' => 'This slug is already taken',
            'image_id.exists' => 'Selected image does not exist',
            'banner_id.exists' => 'Selected banner does not exist',
            'url.url' => 'URL must be a valid URL',
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

