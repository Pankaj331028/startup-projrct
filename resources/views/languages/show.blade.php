@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('crud.languages.title_singular') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-sm btn-primary" href="{{ route('admin.languages.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('crud.languages.fields.language_name') }}
                        </th>
                        <td>
                            {{ $language->language_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('crud.languages.fields.language_short_code') }}
                        </th>
                        <td>
                            {{ $language->language_short_code ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
