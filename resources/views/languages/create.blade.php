@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('crud.languages.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.languages.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="language_name">{{ trans('crud.languages.fields.language_name') }}</label>
                <input class="form-control {{ $errors->has('language_name') ? 'is-invalid' : '' }}" type="text" name="language_name" id="language_name" value="{{ old('language_name', '') }}" required>
            </div>
            <div class="form-group">
                <label class="required" for="language_short_code">{{ trans('crud.languages.fields.language_short_code') }}</label>
                <input class="form-control {{ $errors->has('language_short_code') ? 'is-invalid' : '' }}" type="text" name="language_short_code" id="language_short_code" value="{{ old('language_short_code', '') }}" required>
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
