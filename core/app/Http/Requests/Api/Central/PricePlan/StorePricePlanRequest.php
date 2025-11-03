<?php

namespace App\Http\Requests\Api\Central\PricePlan;

use Illuminate\Foundation\Http\FormRequest;

class StorePricePlanRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'features' => ['nullable', 'string'],
            'type' => ['required', 'integer', 'in:0,1'], // 0 = monthly, 1 = yearly
            'status' => ['required', 'integer', 'in:0,1'], // 0 = inactive, 1 = active
            'price' => ['required', 'numeric', 'min:0'],
            'free_trial' => ['nullable', 'integer', 'min:0'],
            'faq' => ['nullable', 'string'],
            'page_permission_feature' => ['nullable', 'boolean'],
            'blog_permission_feature' => ['nullable', 'boolean'],
            'product_permission_feature' => ['nullable', 'boolean'],
            'storage_permission_feature' => ['nullable', 'boolean'],
            'package_badge' => ['nullable', 'string', 'max:255'],
            'package_description' => ['nullable', 'string'],
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
            'title.required' => 'العنوان مطلوب',
            'title.max' => 'العنوان يجب ألا يتجاوز 255 حرفاً',
            'type.required' => 'النوع مطلوب',
            'type.in' => 'النوع يجب أن يكون شهري أو سنوي',
            'status.required' => 'الحالة مطلوبة',
            'status.in' => 'الحالة يجب أن تكون نشط أو غير نشط',
            'price.required' => 'السعر مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقماً',
            'price.min' => 'السعر يجب أن يكون أكبر من أو يساوي صفر',
        ];
    }
}

