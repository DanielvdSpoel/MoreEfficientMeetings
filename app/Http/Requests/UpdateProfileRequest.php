<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user()->id],
            'departments' => ['sometimes', 'array'],
            'departments.*' => ['sometimes', 'exists:department,id'],
            'skills' => ['sometimes', 'array'],
            'skills.*' => ['sometimes', 'exists:skills,id']
        ];
    }
}
