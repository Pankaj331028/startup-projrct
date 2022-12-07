@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ $pricingPlan->pricing_plan_name }} - {{ trans('global.edit') }} {{ trans('crud.pricing_plan_details.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.pricing-plans.pricing-plan-details.update", ['pricing_plan' => $pricingPlan, 'pricing_plan_detail' => $pricingPlanDetail]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="required" for="pricing_plan_keyname">{{ trans('crud.pricing_plan_details.fields.pricing_plan_keyname') }}</label>
                <select class="form-control select2 {{ $errors->has('pricing_plan_keyname') ? 'is-invalid' : '' }}" style="width: 100%;" name="pricing_plan_keyname" id="pricing_plan_keyname" required>
                    <option value="">{{ trans('global.please_select', ['option' => '']) }}</option>
                    @foreach(\App\Models\PricingPlanDetail::PRICING_PLAN_KEYNAMES as $key => $value)
                        <option value="{{ $key }}" {{ (old('pricing_plan_keyname') ? old('pricing_plan_keyname') : ($pricingPlanDetail->pricing_plan_keyname ?? '')) == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="required" for="pricing_plan_value">{{ trans('crud.pricing_plan_details.fields.pricing_plan_value') }}</label>
                <input class="form-control {{ $errors->has('pricing_plan_value') ? 'is-invalid' : '' }}" type="text" name="pricing_plan_value" id="pricing_plan_value" value="{{ old('pricing_plan_value', $pricingPlanDetail->pricing_plan_value) }}" required>
            </div>
            <div class="form-group">
                <label for="valid_from">{{ trans('crud.pricing_plan_details.fields.valid_from') }}</label>
                <input class="form-control datetime {{ $errors->has('valid_from') ? 'is-invalid' : '' }}" type="text" name="valid_from" id="valid_from" value="{{ old('valid_from', $pricingPlanDetail->valid_from) }}">
            </div>
            <div class="form-group">
                <label for="valid_to">{{ trans('crud.pricing_plan_details.fields.valid_to') }}</label>
                <input class="form-control datetime {{ $errors->has('valid_to') ? 'is-invalid' : '' }}" type="text" name="valid_to" id="valid_to" value="{{ old('valid_to', $pricingPlanDetail->valid_to) }}">
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.update') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
