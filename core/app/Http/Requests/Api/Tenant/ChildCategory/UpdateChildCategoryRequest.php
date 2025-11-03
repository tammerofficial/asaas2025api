<?php

namespace App\Http\Requests\Api\Tenant\ChildCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateChildCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $childCategoryId = $this->route('childCategory')->id ?? null;
        
        return [
            'category_id' => ['sometimes', 'required', 'integer', 'exists:categories,id'],
            'sub_category_id' => ['sometimes', 'required', 'integer', 'exists:sub_categories,id'],
            'name' => ['sometimes', 'required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', Rule::unique('child_categories', 'slug')->ignore($childCategoryId)],
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
            'sub_category_id.required' => 'Sub category is required',
            'sub_category_id.exists' => 'Selected sub category does not exist',
            'name.required' => 'Child category name is required',
            'name.max' => 'Child category name must not exceed 191 characters',
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

