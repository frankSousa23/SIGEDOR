<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'site_id' => ['required', 'exists:sites,id'],
            'area_id' => ['required', 'exists:area_options,id'],
            // ... otras reglas ...
        ];
    }

    public function authorize()
    {
        return true;
    }
}
