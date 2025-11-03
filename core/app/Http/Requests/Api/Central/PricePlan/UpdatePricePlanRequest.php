<?php

namespace App\Http\Requests\Api\Central\PricePlan;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePricePlanRequest extends FormRequest
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
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'package_description' => ['nullable', 'string', 'max:5000'],
            'features' => ['nullable', 'array'],
            'type' => ['sometimes', 'required', 'integer', 'in:0,1'],
            'status' => ['sometimes', 'required', 'integer', 'in:0,1'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0', 'max:999999.99'],
            'free_trial' => ['nullable', 'integer', 'min:0'],
            'faq' => ['nullable', 'array'],
            'package_badge' => ['nullable', 'string', 'max:50'],
            'page_permission_feature' => ['nullable', 'boolean'],
            'blog_permission_feature' => ['nullable', 'boolean'],
            'product_permission_feature' => ['nullable', 'boolean'],
            'storage_permission_feature' => ['nullable', 'boolean'],
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
            'title.required' => 'Plan title is required',
            'title.max' => 'Title must not exceed 255 characters',
            'type.required' => 'Plan type is required',
            'type.in' => 'Invalid plan type. Allowed values: 0 (monthly), 1 (yearly)',
            'status.required' => 'Plan status is required',
            'status.in' => 'Invalid status. Allowed values: 0 (inactive), 1 (active)',
            'price.required' => 'Plan price is required',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price must be zero or greater',
            'price.max' => 'Price must not exceed 999999.99',
        ];
    }
}
