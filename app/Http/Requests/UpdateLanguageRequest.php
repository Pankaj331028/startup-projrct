<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLanguageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('update-language');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'language_name' => [
                'required',
                'max:191',
                'string'
            ],
            'language_short_code' => [
                'required',
                'string',
                'max:10',
                'unique:languages,language_short_code,'.request()->route('language')->id
            ]
        ];
    }
}
