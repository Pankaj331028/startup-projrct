@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('crud.tags.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.tags.update", ['tag' => $tag]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="required" for="tag_type">{{ trans('crud.tags.fields.tag_type') }}</label>
                <select class="form-control select2 {{ $errors->has('tag_type') ? 'is-invalid' : '' }}" style="width: 100%;" name="tag_type" id="tag_type">
                    @foreach($tagTypes as $id => $tagType)
                        <option value="{{ $id }}"  {{ (old('tag_type') ? old('tag_type') : ($tag->tag_type ?? '')) == $id ? 'selected' : '' }}>{{ $tagType }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="required" for="tag_name">{{ trans('crud.tags.fields.tag_name') }}</label>
                <input class="form-control {{ $errors->has('tag_name') ? 'is-invalid' : '' }}" type="text" name="tag_name" id="tag_name" value="{{ old('tag_name', $tag->tag_name) }}">
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
