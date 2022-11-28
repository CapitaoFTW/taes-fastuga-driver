<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateOrderRequest extends FormRequest
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
            'ticket_number' => ['required','string'],
            'delivered_by' => ['required', Rule::exists('users', 'id'), Rule::prohibitedIf($this->type != 'D')],
            'status' => 'required|in:P,R',
            'total_price' => 'required|numeric|between:0,99.99',
        ];
    }
}
