<?php
namespace App\Http\Requests\Api\Tenant\SupportTicket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreSupportTicketRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['title' => ['required', 'string', 'max:255'], 'via' => ['nullable', 'string'], 'operating_system' => ['nullable', 'string'], 'user_agent' => ['nullable', 'string'], 'description' => ['required', 'string'], 'subject' => ['nullable', 'string', 'max:255'], 'status' => ['nullable', 'string'], 'priority' => ['nullable', 'string'], 'user_id' => ['nullable', 'integer', 'exists:users,id'], 'department_id' => ['nullable', 'integer', 'exists:support_departments,id']]; }
    protected function failedValidation(Validator $validator) { throw new HttpResponseException(response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422)); }
}

