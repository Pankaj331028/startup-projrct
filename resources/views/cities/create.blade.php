@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('crud.cities.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.cities.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="country_id">{{ trans('crud.cities.fields.country') }}</label>
                <select class="form-control select2 {{ $errors->has('country_id') ? 'is-invalid' : '' }}" style="width: 100%;" name="country_id" id="country_id" required>
                    @foreach($countries as $id => $countryName)
                        <option value="{{ $id }}" {{ old('country_id') == $id ? 'selected' : '' }}>{{ $countryName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="required" for="city_name">{{ trans('crud.cities.fields.city_name') }}</label>
                <input class="form-control {{ $errors->has('city_name') ? 'is-invalid' : '' }}" type="text" name="city_name" id="city_name" value="{{ old('city_name', '') }}" required>
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
