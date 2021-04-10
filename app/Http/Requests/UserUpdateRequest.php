<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'email' => 'email',
            'name' => 'bail | min:3 | string',
            'username' => 'bail | min:3 | max:15',
            'description' => 'min:10',
            'resume' => 'bail | file | mimes:pdf'
        ];
    }
}
