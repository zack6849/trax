<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;

class CreateCarRequest extends FormRequest
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
        return
        [
            //this is silly, but that's the year the first car was invented
            'year' => 'required|integer|gte:1886',
            'make' => 'required|string',
            'model' => 'required|string'
        ];
    }
}
