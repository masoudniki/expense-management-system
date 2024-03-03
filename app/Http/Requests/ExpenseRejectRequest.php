<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRejectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'expense_requests' => ['array'],
            'expense_requests.*.id' => ['integer','exists:expense_requests,id,is_confirmed,null'],
            'expense_requests.*.reject_reason' => ['string','max:255']
        ];
    }
}
