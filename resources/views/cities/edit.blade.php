@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('crud.cities.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.cities.update", ['city' => $city]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="required" for="country_id">{{ trans('crud.cities.fields.country') }}</label>
                <select class="form-control select2 {{ $errors->has('country_id') ? 'is-invalid' : '' }}" style="width: 100%;" name="country_id" id="country_id">
                    @foreach($countries as $id => $countryName)
                        <option value="{{ $id }}"  {{ (old('country_id') ? old('country_id') : ($city->country_id ?? '')) == $id ? 'selected' : '' }}>{{ $countryName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="required" for="city_name">{{ trans('crud.cities.fields.city_name') }}</label>
                <input class="form-control {{ $errors->has('city_name') ? 'is-invalid' : '' }}" type="text" name="city_name" id="city_name" value="{{ old('city_name', $city->city_name) }}">
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
