<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'description' => ['required','string'],
            'national_code' => ['required','string','exists:users,national_code'],
            'expense_request_type_id' => ['required','int','exists:expense_requests_types,id'],
            'iban' => ['required','string'],
            'attachments' => ['array'],
            'attachments.*' => ['required','mimes:jpg,jpeg,png,pdf','max:1024']
        ];
    }
}
