<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeetingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i:s',
            'duration' => 'required|integer|min:1',
            'attendees' => 'required|array',
            'attendees.*' => 'required|integer|exists:users,id',
            'title' => 'required|string',
            'description' => 'required|string',
        ];
    }
}
