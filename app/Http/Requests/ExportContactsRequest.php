<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportContactsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contacts' => 'required|array',
            'contacts.*' => 'exists:contacts,id'
        ];
    }
}
