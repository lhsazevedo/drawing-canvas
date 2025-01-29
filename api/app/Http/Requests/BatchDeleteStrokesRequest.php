<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BatchDeleteStrokesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'uuids' => 'required|array',
            'uuids.*' => 'required|string',
        ];
    }
}
