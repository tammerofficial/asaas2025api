<?php
namespace App\Http\Requests\Api\Tenant\Newsletter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
class UpdateNewsletterRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { $newsletter = $this->route('newsletter'); $id = $newsletter ? $newsletter->id : null; return ['email' => ['sometimes', 'required', 'email', Rule::unique('newsletters', 'email')->ignore($id)], 'status' => ['nullable', 'integer', 'in:0,1']]; }
    protected function failedValidation(Validator $validator) { throw new HttpResponseException(response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422)); }
}

