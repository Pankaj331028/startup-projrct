@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('crud.pricing_plans.title_singular') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-sm btn-primary" href="{{ route('admin.pricing-plans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('crud.pricing_plans.fields.pricing_plan_name') }}
                        </th>
                        <td>
                            {{ $pricingPlan->pricing_plan_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('crud.pricing_plans.fields.pricing_plan_type') }}
                        </th>
                        <td>
                            {{ \App\Models\PricingPlan::PLAN_TYPE[$pricingPlan->pricing_plan_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('crud.pricing_plans.fields.pricing_plan_payment_frequency') }}
                        </th>
                        <td>
                            {{ \App\Models\PricingPlan::PLAN_PAYMENT_FREQUENCY[$pricingPlan->pricing_plan_payment_frequency] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('crud.pricing_plans.fields.pricing_plan_duration') }}
                        </th>
                        <td>
                            {{ $pricingPlan->pricing_plan_duration ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
