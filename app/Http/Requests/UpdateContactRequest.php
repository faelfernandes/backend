<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PhoneNumber;
use App\Rules\ZipCode;
use App\Rules\BrazilianState;

class UpdateContactRequest extends FormRequest
{
    private const RULE_REQUIRED_STRING = 'required|string';
    private const RULE_REQUIRED_EMAIL = 'required|email';

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('phone')) {
            $this->merge([
                'phone' => preg_replace('/[^0-9]/', '', $this->phone)
            ]);
        }

        if ($this->has('address.zip_code')) {
            $this->merge([
                'address' => array_merge($this->address, [
                    'zip_code' => preg_replace('/[^0-9]/', '', $this->address['zip_code'])
                ])
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => self::RULE_REQUIRED_STRING,
            'email' => self::RULE_REQUIRED_EMAIL,
            'phone' => ['required', 'min:10', 'max:11', new PhoneNumber],
            'address' => 'required|array',
            'address.zip_code' => ['required', 'size:8', new ZipCode],
            'address.state' => ['required', new BrazilianState],
            'address.city' => self::RULE_REQUIRED_STRING,
            'address.neighborhood' => self::RULE_REQUIRED_STRING,
            'address.street' => self::RULE_REQUIRED_STRING,
            'address.number' => self::RULE_REQUIRED_STRING,
        ];
    }
}
