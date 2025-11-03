<?php
namespace App\Http\Requests\Api\Tenant\Tax;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreTaxRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['name' => ['required', 'string', 'max:255'], 'rate' => ['required', 'numeric', 'min:0', 'max:100'], 'status' => ['nullable', 'integer', 'in:0,1']]; }
    protected function failedValidation(Validator $validator) { throw new HttpResponseException(response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422)); }
}

