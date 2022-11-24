<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'type' => 'required|in:C,EC,EM,ED',
            'photo_file' => 'nullable|file|image',
            'license_plate' => ['required', 'string', 'regex:/[A-z]{2}-[0-9]{2}-[A-z]{2}|[0-9]{2}-[A-z]{2}-[0-9]{2}|[0-9]{2}-[0-9]{2}-[A-z]{2}|[A-z]{2}-[0-9]{2}-[0-9]{2}|[A-z]{2}[0-9]{2}[A-z]{2}|[0-9]{2}[A-z]{2}[0-9]{2}|[0-9]{2}[0-9]{2}[A-z]{2}|[A-z]{2}[0-9]{2}[0-9]{2}/'],
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