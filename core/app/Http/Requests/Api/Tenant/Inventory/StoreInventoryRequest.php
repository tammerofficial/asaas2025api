<?php

namespace App\Http\Requests\Api\Tenant\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreInventoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'stock_count' => ['required', 'integer', 'min:0'],
            'stock_alert_quantity' => ['nullable', 'integer', 'min:0'],
            'sold_count' => ['nullable', 'integer', 'min:0'],
            'admin_id' => ['nullable', 'integer', 'exists:admins,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Product is required',
            'product_id.exists' => 'Selected product does not exist',
            'stock_count.required' => 'Stock count is required',
            'stock_count.min' => 'Stock count must be zero or greater',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422)
        );
    }
}

