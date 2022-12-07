<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCountryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('update-country');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'default_language_id' => [
                'required',
                'integer',
                'exists:languages,id'
            ],
            'country_name' => [
                'required',
                'string',
                'max:191'
            ],
            'country_code' => [
                'required',
                'string',
                'max:3',
                'unique:countries,country_code,'.request()->route('country')->id
            ],
        ];
    }

    public function attributes()
    {
        return [
            'default_language_id' => 'default language',
        ];
    }
}
