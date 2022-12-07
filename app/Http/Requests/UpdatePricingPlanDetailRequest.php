<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePricingPlanDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('update-pricing-plan');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        [$pricingPlanKeyNames, $planTypeValues] = Arr::divide(\App\Models\PricingPlanDetail::PRICING_PLAN_KEYNAMES);

        return [
            'pricing_plan_keyname' => [
                'required',
                'string',
                'in:'.implode(',', $pricingPlanKeyNames),
            ],
            'pricing_plan_value' => [
                'required',
            ],
            'valid_from' => [
                'nullable',
                'date_format:Y-m-d H:i:s'
            ],
            'valid_to' => [
                'nullable',
                'date_format:Y-m-d H:i:s'
            ],
        ];
    }
}
