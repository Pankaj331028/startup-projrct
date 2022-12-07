<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class StorePricingPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('create-pricing-plan');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        [$planTypeKeys, $planTypeValues] = Arr::divide(\App\Models\PricingPlan::PLAN_TYPE);
        [$planPaymentFrequencyKeys, $planPaymentFrequencyValues] = Arr::divide(\App\Models\PricingPlan::PLAN_PAYMENT_FREQUENCY);

        return [
            'pricing_plan_name' => [
                'required',
                'max:191',
                'unique:pricing_plans,pricing_plan_name',
            ],
            'pricing_plan_type' => [
                'required',
                'string',
                'in:'.implode(',', $planTypeKeys),
            ],
            'pricing_plan_payment_frequency' => [
                'required',
                'string',
                'in:'.implode(',', $planPaymentFrequencyKeys),
            ],
            'pricing_plan_duration' => [
                'required',
                'integer'
            ],
            'amount' => [
                'required',
                'numeric'
            ],
            'tax' => [
                'required',
                'numeric'
            ],
            'valid_from' => [
                'required',
                'date_format:Y-m-d H:i:s'
            ],
            'valid_to' => [
                'required',
                'date_format:Y-m-d H:i:s'
            ],
        ];
    }
}
