<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;

class UpdateUserApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('update-profile');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
                'nullable',
                'string',
                'max:191'
            ],
            'first_name' => [
                'nullable',
                'string',
                'max:191',
            ],
            'last_name' => [
                'nullable',
                'string',
                'max:191',
            ],
            'email' => [
                'nullable',
                'email'
            ],
            'birthday' => [
                'nullable',
                'date_format:Y-m-d'
            ],
            'gender' => [
                'nullable',
                'in:'.implode(',', \App\Models\Tag::where('tag_type', 'gender')->pluck('tag_slug')->toArray())
            ],
            'about_the_user' => [
                'nullable'
            ],
            'intro' => [
                'nullable'
            ],
            'health_information_title' => [
                'nullable'
            ],
            'health_information_details' => [
                'nullable'
            ],
            'health_information_date' => [
                'nullable'
            ],
            'health_information_info_url' => [
                'nullable',
                'url'
            ],
        ];
    }

    /**
     * custom validation error handling (Vivek: 29-May 01:33 PM)
     *
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            [$requestKeys, $requestValues] = Arr::divide(Request::all());
            $userModelFillables = (new \App\Models\User)->fillable;

            // if there is no request parameter in request
            if(empty($requestKeys)) {
                $validator->errors()->add('invalid_request', 'Invalid request');
            }

            // if requested parameter does not exists in users table column
            $diff = array_diff($requestKeys, $userModelFillables);

            if(count($diff)) {
                foreach($diff as $d) {
                    $validator->errors()->add($d.'_invalid_column', $d.' is invalid');
                }
            }
        });
    }
}
