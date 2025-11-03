<?php

namespace App\Http\Requests\Api\Tenant\Shipping\Method;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreShippingMethodRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'zone_id' => ['nullable', 'integer', 'exists:zones,id'],
            'is_default' => ['nullable', 'boolean'],
            'options' => ['nullable', 'array'],
            'options.title' => ['nullable', 'string', 'max:255'],
            'options.status' => ['nullable', 'boolean'],
            'options.tax_status' => ['nullable', 'boolean'],
            'options.cost' => ['nullable', 'numeric', 'min:0'],
            'options.minimum_order_amount' => ['nullable', 'numeric', 'min:0'],
            'options.coupon' => ['nullable', 'string', 'max:255'],
            'options.setting_preset' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Shipping method name is required',
            'name.max' => 'Shipping method name must not exceed 255 characters',
            'zone_id.exists' => 'Selected shipping zone does not exist',
            'options.cost.numeric' => 'Cost must be a number',
            'options.cost.min' => 'Cost must be zero or greater',
            'options.minimum_order_amount.numeric' => 'Minimum order amount must be a number',
            'options.minimum_order_amount.min' => 'Minimum order amount must be zero or greater',
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

