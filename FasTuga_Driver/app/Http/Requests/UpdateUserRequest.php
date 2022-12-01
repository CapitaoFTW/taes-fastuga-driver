<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            //'photo_file' => 'nullable|file|image',
            'license_plate' => ['required', 'string', Rule::unique('drivers', 'license_plate')->ignore($this->id), 'regex:/[A-z]{2}-[0-9]{2}-[A-z]{2}|[0-9]{2}-[A-z]{2}-[0-9]{2}|[0-9]{2}-[0-9]{2}-[A-z]{2}|[A-z]{2}-[0-9]{2}-[0-9]{2}|[A-z]{2}[0-9]{2}[A-z]{2}|[0-9]{2}[A-z]{2}[0-9]{2}|[0-9]{2}[0-9]{2}[A-z]{2}|[A-z]{2}[0-9]{2}[0-9]{2}/'],
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
