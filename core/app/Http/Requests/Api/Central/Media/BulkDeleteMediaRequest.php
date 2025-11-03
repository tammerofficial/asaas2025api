<?php

namespace App\Http\Requests\Api\Central\Media;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BulkDeleteMediaRequest extends FormRequest
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
            'media_ids' => ['required', 'array', 'min:1'],
            'media_ids.*' => ['required', 'integer', 'exists:media_uploaders,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'media_ids.required' => 'Media IDs are required',
            'media_ids.array' => 'Media IDs must be an array',
            'media_ids.min' => 'At least one media ID must be provided',
            'media_ids.*.required' => 'Each media ID is required',
            'media_ids.*.integer' => 'Each media ID must be an integer',
            'media_ids.*.exists' => 'One or more media IDs do not exist',
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

