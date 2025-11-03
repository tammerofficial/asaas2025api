<?php

namespace App\Http\Requests\Api\Tenant\SubCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateSubCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $subCategoryId = $this->route('subCategory')->id ?? null;
        
        return [
            'category_id' => ['sometimes', 'required', 'integer', 'exists:categories,id'],
            'name' => ['sometimes', 'required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', Rule::unique('sub_categories', 'slug')->ignore($subCategoryId)],
            'description' => ['nullable', 'string'],
            'image_id' => ['nullable', 'integer', 'exists:media_uploaders,id'],
            'status_id' => ['nullable', 'integer', 'exists:statuses,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Selected category does not exist',
            'name.required' => 'Sub category name is required',
            'name.max' => 'Sub category name must not exceed 191 characters',
            'slug.unique' => 'This slug is already taken',
            'image_id.exists' => 'Selected image does not exist',
            'status_id.exists' => 'Selected status does not exist',
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

