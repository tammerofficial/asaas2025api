<?php
namespace App\Http\Requests\Api\Central\SupportTicket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UpdateSupportTicketRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['title' => ['sometimes', 'required', 'string', 'max:255'], 'status' => ['nullable', 'string'], 'priority' => ['nullable', 'string'], 'admin_id' => ['nullable', 'integer', 'exists:admins,id'], 'department_id' => ['nullable', 'integer', 'exists:support_departments,id']]; }
    protected function failedValidation(Validator $validator) { throw new HttpResponseException(response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422)); }
}

