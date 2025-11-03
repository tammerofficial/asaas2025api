<?php
namespace App\Http\Requests\Api\Tenant\Refund;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreRefundRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['user_id' => ['required', 'integer', 'exists:users,id'], 'order_id' => ['required', 'integer', 'exists:product_orders,id'], 'product_id' => ['required', 'integer', 'exists:products,id'], 'status' => ['nullable', 'string']]; }
    protected function failedValidation(Validator $validator) { throw new HttpResponseException(response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422)); }
}

