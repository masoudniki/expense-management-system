<?php

namespace App\Rules;

use App\Services\Payment\PaymentProcessorService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IBANRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!PaymentProcessorService::validateIban($value)){
            $fail('iban is not supported.');
        }
    }
}
