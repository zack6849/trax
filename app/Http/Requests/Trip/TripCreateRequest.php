<?php

namespace App\Http\Requests\Trip;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TripCreateRequest extends FormRequest
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
            'date' => 'required|date|before_or_equal:'.now(), //make sure the date isn't in the future.
            'car_id' => 'required|integer|exists:cars,id',
            'miles' => 'required|numeric|min:0' //ensure the number is positive, I wanted to put 1 but someone could log a half mile trip, I guess.
        ];
    }
}
