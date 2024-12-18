<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetUserPreferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust authorization logic if needed
    }

    public function rules(): array
    {
        return [
            'categories' => 'array',
            'categories.*' => 'string',
            'sources' => 'array',
            'sources.*' => 'string',
            'authors' => 'array',
            'authors.*' => 'string',
        ];
    }
}
