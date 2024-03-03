<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManualExpensePaymentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'expense_request_ids'=>['array'],
            'expense_request_ids.*'=>['integer','exists:expense_requests,id,is_confirmed,true,is_paid,false']
        ];
    }
}
