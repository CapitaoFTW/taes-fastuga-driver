<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->id),],
            'password' => ['required', 'min:3', 'confirmed'],
            'password_confirmation' =>  ['required', 'same:password'],
            'license_plate' => ['required', 'regex:/(^[A-Za-z]{2}-[0-9]{2}-[A-Za-z]{2}$)|^([0-9]{2}-[A-Za-z]{2}-[0-9]{2}$)|(^[0-9]{2}-[0-9]{2}-[A-Za-z]{2}$)|(^[A-Za-z]{2}-[0-9]{2}-[0-9]{2}$)|(^[A-Za-z]{2}[0-9]{2}[A-Za-z]{2}$)|(^[0-9]{2}[A-Za-z]{2}[0-9]{2}$)|(^[0-9]{2}[0-9]{2}[A-Za-z]{2}$)|(^[A-Za-z]{2}[0-9]{2}[0-9]{2}$)/'],
            'phone_number' => ['required', 'max:9', 'regex:/[9][0-9]{8}|[2][0-9]{8}/'],
        ];
    }

    public function messages()
    {
        return [
            'license_plate.regex' => 'The :attribute format must follow Portugal license plate patterns.',
            'phone_number.regex' => 'The :attribute format must follow Portugal phone number patterns.',
        ];
    }
}
