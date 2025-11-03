<?php

namespace App\Http\Requests\Api\Tenant\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateBlogRequest extends FormRequest
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
        $blog = $this->route('blog');
        $blogId = $blog ? $blog->id : null;

        return [
            'category_id' => ['nullable', 'integer', 'exists:blog_categories,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blogs', 'slug')->ignore($blogId)],
            'blog_content' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'author' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'status' => ['nullable', 'integer', 'in:0,1'],
            'image_gallery' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'visibility' => ['nullable', 'integer'],
            'featured' => ['nullable', 'integer', 'in:0,1'],
            'tags' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Blog title is required',
            'title.max' => 'Blog title must not exceed 255 characters',
            'slug.unique' => 'This slug is already taken',
            'category_id.exists' => 'Selected blog category does not exist',
            'status.in' => 'Status must be either 0 (draft) or 1 (published)',
            'featured.in' => 'Featured must be either 0 or 1',
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

