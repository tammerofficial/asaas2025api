<?php
namespace App\Http\Requests\Api\Tenant\Campaign;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreCampaignRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['title' => ['required', 'string', 'max:255'], 'status' => ['nullable', 'integer', 'in:0,1'], 'start_date' => ['nullable', 'date'], 'end_date' => ['nullable', 'date', 'after_or_equal:start_date']]; }
    protected function failedValidation(Validator $validator) { throw new HttpResponseException(response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422)); }
}

