<?php

namespace App\Http\Requests\Api\Tenant\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tag_text' => ['required', 'string', 'max:191', Rule::unique('tags', 'tag_text')],
        ];
    }

    public function messages(): array
    {
        return [
            'tag_text.required' => 'Tag text is required',
            'tag_text.max' => 'Tag text must not exceed 191 characters',
            'tag_text.unique' => 'This tag already exists',
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

