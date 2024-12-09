<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $number = preg_replace('/[^0-9]/', '', $value);

        if (!preg_match('/^[1-9]{2}[0-9]{8,9}$/', $number)) {
            $fail('O campo :attribute deve ser um telefone válido.');
        }
    }
}
