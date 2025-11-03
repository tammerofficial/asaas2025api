<?php

namespace App\Http\Requests\Api\Tenant\Page;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'page_content' => ['nullable', 'string'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages,slug'],
            'visibility' => ['nullable', 'integer', 'in:0,1'],
            'page_builder' => ['nullable', 'integer', 'in:0,1'],
            'status' => ['nullable', 'integer', 'in:0,1'],
            'breadcrumb' => ['nullable', 'integer', 'in:0,1'],
            'navbar_variant' => ['nullable', 'string', 'max:255'],
            'footer_variant' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Page title is required',
            'title.max' => 'Page title must not exceed 255 characters',
            'slug.unique' => 'This slug is already taken',
            'visibility.in' => 'Visibility must be either 0 (hidden) or 1 (visible)',
            'page_builder.in' => 'Page builder must be either 0 or 1',
            'status.in' => 'Status must be either 0 (draft) or 1 (published)',
            'breadcrumb.in' => 'Breadcrumb must be either 0 or 1',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
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

