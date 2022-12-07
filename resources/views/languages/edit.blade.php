@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('crud.languages.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.languages.update", ['language' => $language]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="required" for="language_name">{{ trans('crud.languages.fields.language_name') }}</label>
                <input class="form-control {{ $errors->has('language_name') ? 'is-invalid' : '' }}" type="text" name="language_name" id="language_name" value="{{ old('language_name', $language->language_name) }}">
            </div>
            <div class="form-group">
                <label class="required" for="language_short_code">{{ trans('crud.languages.fields.language_short_code') }}</label>
                <input class="form-control {{ $errors->has('language_short_code') ? 'is-invalid' : '' }}" type="text" name="language_short_code" id="language_short_code" value="{{ old('language_short_code', $language->language_short_code) }}">
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
