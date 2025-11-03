<?php
namespace App\Http\Requests\Api\Tenant\ProductReview;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UpdateProductReviewRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['rating' => ['sometimes', 'required', 'integer', 'min:1', 'max:5'], 'review_text' => ['nullable', 'string'], 'status' => ['nullable', 'integer', 'in:0,1']]; }
    protected function failedValidation(Validator $validator) { throw new HttpResponseException(response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422)); }
}

