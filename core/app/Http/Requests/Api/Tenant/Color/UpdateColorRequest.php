<?php

namespace App\Http\Requests\Api\Tenant\Color;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $colorId = $this->route('color')->id ?? null;
        
        return [
            'name' => ['sometimes', 'required', 'string', 'max:191'],
            'color_code' => ['sometimes', 'required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', Rule::unique('colors', 'slug')->ignore($colorId)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Color name is required',
            'name.max' => 'Color name must not exceed 191 characters',
            'color_code.required' => 'Color code is required',
            'color_code.max' => 'Color code must not exceed 191 characters',
            'slug.unique' => 'This slug is already taken',
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

