<?php

namespace App\Http\Requests\Api\Tenant\Coupon;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateCouponRequest extends FormRequest
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
        $coupon = $this->route('coupon');
        $couponId = $coupon ? $coupon->id : null;

        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'code' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('product_coupons', 'code')->ignore($couponId)],
            'discount' => ['sometimes', 'required', 'numeric', 'min:0'],
            'discount_type' => ['sometimes', 'required', 'string', 'in:percentage,fixed'],
            'discount_on' => ['nullable', 'string', 'max:255'],
            'discount_on_details' => ['nullable', 'string'],
            'expire_date' => ['nullable', 'date', 'after_or_equal:today'],
            'status' => ['nullable', 'string', 'in:draft,publish'],
            'minimum_quantity' => ['nullable', 'integer', 'min:1'],
            'minimum_spend' => ['nullable', 'numeric', 'min:0'],
            'maximum_spend' => ['nullable', 'numeric', 'min:0', 'gt:minimum_spend'],
            'usage_limit_per_coupon' => ['nullable', 'integer', 'min:1'],
            'usage_limit_per_user' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Coupon title is required',
            'title.max' => 'Coupon title must not exceed 255 characters',
            'code.required' => 'Coupon code is required',
            'code.unique' => 'This coupon code is already taken',
            'discount.required' => 'Discount amount is required',
            'discount.numeric' => 'Discount must be a number',
            'discount.min' => 'Discount must be zero or greater',
            'discount_type.required' => 'Discount type is required',
            'discount_type.in' => 'Discount type must be either percentage or fixed',
            'expire_date.after_or_equal' => 'Expire date must be today or later',
            'status.in' => 'Status must be either draft or publish',
            'maximum_spend.gt' => 'Maximum spend must be greater than minimum spend',
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

