<?php

namespace App\Http\Requests\Api\Tenant\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProductRequest extends FormRequest
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
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'brand_id' => ['nullable', 'integer', 'exists:brands,id'],
            'status_id' => ['nullable', 'integer', 'exists:statuses,id'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'image_id' => ['nullable', 'integer', 'exists:media_uploaders,id'],
            'badge_id' => ['nullable', 'integer', 'exists:badges,id'],
            'min_purchase' => ['nullable', 'integer', 'min:1'],
            'max_purchase' => ['nullable', 'integer', 'gt:min_purchase'],
            'is_refundable' => ['nullable', 'boolean'],
            'is_inventory_warn_able' => ['nullable', 'boolean'],
            'is_in_house' => ['nullable', 'boolean'],
            'is_taxable' => ['nullable', 'boolean'],
            'tax_class_id' => ['nullable', 'integer', 'exists:tax_classes,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required',
            'name.max' => 'Product name must not exceed 255 characters',
            'slug.unique' => 'This slug is already taken',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price must be zero or greater',
            'sale_price.lt' => 'Sale price must be less than regular price',
            'max_purchase.gt' => 'Maximum purchase must be greater than minimum purchase',
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

