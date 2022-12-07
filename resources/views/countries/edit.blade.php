@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('crud.countries.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.countries.update", ['country' => $country]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="required" for="country_name">{{ trans('crud.countries.fields.country_name') }}</label>
                <input class="form-control {{ $errors->has('country_name') ? 'is-invalid' : '' }}" type="text" name="country_name" id="country_name" value="{{ old('country_name', $country->country_name) }}">
                {{-- @if($errors->has('country_name'))
                    <span class="text-danger">{{ $errors->first('country_name') }}</span>
                @endif --}}
            </div>
            <div class="form-group">
                <label class="required" for="country_code">{{ trans('crud.countries.fields.country_code') }}</label>
                <input class="form-control {{ $errors->has('country_code') ? 'is-invalid' : '' }}" type="text" name="country_code" id="country_code" value="{{ old('country_code', $country->country_code) }}">
                {{-- @if($errors->has('country_code'))
                    <span class="text-danger">{{ $errors->first('country_code') }}</span>
                @endif --}}
            </div>
            <div class="form-group">
                <label class="required" for="default_language_id">{{ trans('crud.countries.fields.default_language') }}</label>
                <select class="form-control select2 {{ $errors->has('default_language_id') ? 'is-invalid' : '' }}" style="width: 100%;" name="default_language_id" id="default_language_id">
                    @foreach($languages as $id => $languageName)
                        <option value="{{ $id }}"  {{ (old('default_language_id') ? old('default_language_id') : ($country->default_language_id ?? '')) == $id ? 'selected' : '' }}>{{ $languageName }}</option>
                    @endforeach
                </select>
                {{-- @if($errors->has('default_language_id'))
                    <span class="text-danger">{{ $errors->first('default_language_id') }}</span>
                @endif --}}
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
