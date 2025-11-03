<?php

namespace App\Http\Requests\Api\Tenant\DeliveryOption;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreDeliveryOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'icon' => ['nullable', 'string', 'max:191'],
            'title' => ['required', 'string', 'max:191'],
            'sub_title' => ['nullable', 'string', 'max:191'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Delivery option title is required',
            'title.max' => 'Delivery option title must not exceed 191 characters',
            'sub_title.max' => 'Sub title must not exceed 191 characters',
            'icon.max' => 'Icon must not exceed 191 characters',
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

