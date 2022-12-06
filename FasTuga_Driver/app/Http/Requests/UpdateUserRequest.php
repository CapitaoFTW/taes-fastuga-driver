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
            'name' => 'string|max:255',
            'photo' => ['starts_with:data:image/png,data:image/jpeg,data:image/svg'],
            'license_plate' => ['string', Rule::unique('users', 'license_plate')->ignore($this->id), 'regex:/[A-z]{2}-[0-9]{2}-[A-z]{2}|[0-9]{2}-[A-z]{2}-[0-9]{2}|[0-9]{2}-[0-9]{2}-[A-z]{2}|[A-z]{2}-[0-9]{2}-[0-9]{2}|[A-z]{2}[0-9]{2}[A-z]{2}|[0-9]{2}[A-z]{2}[0-9]{2}|[0-9]{2}[0-9]{2}[A-z]{2}|[A-z]{2}[0-9]{2}[0-9]{2}/'],
            'phone_number' => 'max:9|starts_with:2,9',
        ];
    }

    public function messages()
    {
        return [
            'photo.starts_with' => 'The image uploaded must be in PNG/JPG/SVG format.',
            'license_plate.regex' => 'The :attribute format must follow Portugal license plate patterns.',
        ];
    }
}
