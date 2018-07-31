<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'access_level' => 'required|integer|min:1|max:3',
            'business_name' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'network_visible' => 'nullable|boolean',
            'suburb' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
        ];
    }
}
