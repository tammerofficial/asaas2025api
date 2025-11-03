<?php
namespace App\Http\Requests\Api\Tenant\Refund;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UpdateRefundRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['status' => ['nullable', 'string']]; }
    protected function failedValidation(Validator $validator) { throw new HttpResponseException(response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422)); }
}

