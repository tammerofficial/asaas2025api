<?php

namespace App\Http\Requests\Api\Tenant\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug'],
            'description' => ['nullable', 'string', 'max:5000'],
            'image_id' => ['nullable', 'integer', 'exists:media_uploaders,id'],
            'status_id' => ['nullable', 'integer', 'exists:statuses,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required',
            'name.max' => 'Category name must not exceed 255 characters',
            'slug.unique' => 'This slug is already taken',
            'description.max' => 'Description must not exceed 5000 characters',
            'image_id.exists' => 'Selected image does not exist',
            'status_id.exists' => 'Selected status does not exist',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('name') && !$this->has('slug')) {
            $this->merge([
                'slug' => Str::slug($this->name),
            ]);
        }
    }
}

