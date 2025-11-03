<?php

namespace App\Http\Requests\Api\Tenant\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
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
            'status' => ['required', 'string', Rule::in(['pending', 'processing', 'complete', 'cancel', 'on_hold'])],
            'payment_status' => ['nullable', 'string', Rule::in(['pending', 'success', 'failed', 'cancel'])],
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
            'status.required' => 'Order status is required',
            'status.in' => 'Invalid order status. Allowed values: pending, processing, complete, cancel, on_hold',
            'payment_status.in' => 'Invalid payment status. Allowed values: pending, success, failed, cancel',
        ];
    }
}

