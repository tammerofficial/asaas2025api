<?php

namespace App\Http\Requests\Api\Central\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTenantRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:255'],
            'user_id' => ['required', 'exists:users,id'],
            'domain' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('domains', 'domain'),
            ],
            'data' => ['nullable', 'array'],
            'expire_date' => ['nullable', 'date', 'after:today'],
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
            'user_id.required' => 'معرّف المستخدم مطلوب',
            'user_id.exists' => 'المستخدم المحدد غير موجود',
            'domain.required' => 'النطاق مطلوب',
            'domain.unique' => 'هذا النطاق مستخدم بالفعل',
            'domain.regex' => 'النطاق يجب أن يحتوي على أحرف صغيرة وأرقام وشرطات فقط',
            'expire_date.date' => 'تاريخ الانتهاء يجب أن يكون تاريخاً صحيحاً',
            'expire_date.after' => 'تاريخ الانتهاء يجب أن يكون بعد اليوم',
        ];
    }
}

