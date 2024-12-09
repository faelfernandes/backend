<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ZipCode implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $zipcode = preg_replace('/[^0-9]/', '', $value);

        if (!preg_match('/^[0-9]{8}$/', $zipcode)) {
            $fail('O campo :attribute deve ser um CEP válido.');
        }
    }
}
