@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('crud.pricing_plans.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.pricing-plans.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="pricing_plan_name">{{ trans('crud.pricing_plans.fields.pricing_plan_name') }}</label>
                <input class="form-control {{ $errors->has('pricing_plan_name') ? 'is-invalid' : '' }}" type="text" name="pricing_plan_name" id="pricing_plan_name" value="{{ old('pricing_plan_name', '') }}" required>
            </div>
            <div class="form-group">
                <label class="required" for="pricing_plan_type">{{ trans('crud.pricing_plans.fields.pricing_plan_type') }}</label>
                <select class="form-control select2 {{ $errors->has('pricing_plan_type') ? 'is-invalid' : '' }}" style="width: 100%;" name="pricing_plan_type" id="pricing_plan_type" required>
                    <option value="">{{ trans('global.please_select', ['option' => '']) }}</option>
                    @foreach(\App\Models\PricingPlan::PLAN_TYPE as $key => $value)
                        <option value="{{ $key }}" {{ old('pricing_plan_type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="required" for="pricing_plan_payment_frequency">{{ trans('crud.pricing_plans.fields.pricing_plan_payment_frequency') }}</label>
                <select class="form-control select2 {{ $errors->has('pricing_plan_payment_frequency') ? 'is-invalid' : '' }}" style="width: 100%;" name="pricing_plan_payment_frequency" id="pricing_plan_payment_frequency" required>
                    <option value="">{{ trans('global.please_select', ['option' => '']) }}</option>
                    @foreach(\App\Models\PricingPlan::PLAN_PAYMENT_FREQUENCY as $key => $value)
                        <option value="{{ $key }}" {{ old('pricing_plan_payment_frequency') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="required" for="pricing_plan_duration">{{ trans('crud.pricing_plans.fields.pricing_plan_duration') }}</label>
                <input class="form-control {{ $errors->has('pricing_plan_duration') ? 'is-invalid' : '' }}" type="number" name="pricing_plan_duration" id="pricing_plan_duration" value="{{ old('pricing_plan_duration', '') }}" required>
            </div>
            <div class="form-group">
                <label class="required" for="amount">{{ trans('crud.pricing_plans.fields.amount') }}</label>
                <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" name="amount" id="amount" value="{{ old('amount', '') }}" required>
            </div>
            <div class="form-group">
                <label class="required" for="tax">{{ trans('crud.pricing_plans.fields.tax') }}</label>
                <input class="form-control {{ $errors->has('tax') ? 'is-invalid' : '' }}" type="number" name="tax" id="tax" value="{{ old('tax', '') }}" required>
            </div>
            <div class="form-group">
                <label for="valid_from">{{ trans('crud.pricing_plans.fields.valid_from') }}</label>
                <input class="form-control datetime {{ $errors->has('valid_from') ? 'is-invalid' : '' }}" type="text" name="valid_from" id="valid_from" value="{{ old('valid_from', '') }}">
            </div>
            <div class="form-group">
                <label for="valid_to">{{ trans('crud.pricing_plans.fields.valid_to') }}</label>
                <input class="form-control datetime {{ $errors->has('valid_to') ? 'is-invalid' : '' }}" type="text" name="valid_to" id="valid_to" value="{{ old('valid_to', '') }}">
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
