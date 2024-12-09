<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BrazilianState implements ValidationRule
{
    protected $states = [
        'AC',
        'AL',
        'AP',
        'AM',
        'BA',
        'CE',
        'DF',
        'ES',
        'GO',
        'MA',
        'MT',
        'MS',
        'MG',
        'PA',
        'PB',
        'PR',
        'PE',
        'PI',
        'RJ',
        'RN',
        'RS',
        'RO',
        'RR',
        'SC',
        'SP',
        'SE',
        'TO',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array(strtoupper($value), $this->states)) {
            $fail('O campo :attribute deve ser um estado brasileiro válido.');
        }
    }
}
