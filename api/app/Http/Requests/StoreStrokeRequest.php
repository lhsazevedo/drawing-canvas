<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStrokeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'uuid' => 'required|uuid',
            'color' => 'required|string',
            'size' => 'required|int',
            'points.*' => 'required|array',
            'points.*.*' => 'required|int',
            'min_x' => 'required|int',
            'min_y' => 'required|int',
            'max_x' => 'required|int',
            'max_y' => 'required|int',
        ];
    }
}
