@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('crud.countries.title_singular') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-sm btn-primary" href="{{ route('admin.countries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('crud.countries.fields.country_name') }}
                        </th>
                        <td>
                            {{ $country->country_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('crud.countries.fields.country_code') }}
                        </th>
                        <td>
                            {{ $country->country_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('crud.countries.fields.default_language_short_code') }}
                        </th>
                        <td>
                            {{ $country->defaultLanguage->language_short_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('crud.countries.fields.default_language') }}
                        </th>
                        <td>
                            {{ $country->defaultLanguage->language_name }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
