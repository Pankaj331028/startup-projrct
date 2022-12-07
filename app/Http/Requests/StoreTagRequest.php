<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('create-tag');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        [$key, $value] = Arr::divide(\App\Models\Tag::TAG_TYPES);

        return [
            'tag_type' => [
                'required',
                'string',
                'in:'.implode(',', $key)
            ],
            'tag_name' => [
                'required',
                'string',
                'max:191'
            ],
            'tag_slug' => [
                'required',
                Rule::unique('tags')->where(fn ($query) => $query->where('tag_type', $this->tag_type))
            ]
        ];
    }

    public function prepareForValidation()
    {
        return $this->merge([
            'tag_slug' => Str::slug($this->tag_name)
        ]);
    }
}
