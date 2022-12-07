<?php

namespace App\Http\Controllers;
use Alert;
use App\Models\Tag;
use App\Models\Country;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Requests\MassDestroyTagRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Database\Eloquent\Builder;

class TagController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('read-tag'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inputSearchString = $request->input('s', '');
        $tags = Tag::select('id','tag_name','tag_type')
            ->when($inputSearchString, function($query) use ($inputSearchString) {
                $query->where(function($query) use ($inputSearchString) {
                    $query->orWhere('tag_name', 'LIKE', '%'.$inputSearchString.'%')
                        ->orWhere('tag_type', 'LIKE', '%'.$inputSearchString.'%');
                });
            })
            ->isActive()
            ->orderBy('tag_name')
            ->paginate(config('app-config.datatable_default_row_count', 25))
            ->withQueryString();

        return view('tags.index', [
            'tags' => $tags,
        ]);
    }

    public function create()
    {
        abort_if(!auth()->user()->can('create-tag'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tagTypes = ['' => 'Please Select'] + Arr::sort(Tag::TAG_TYPES);
        return view('tags.create', compact('tagTypes'));
    }

    public function store(StoreTagRequest $request)
    {
        Tag::create($request->validated());

        toast(__('global.crud_actions', ['module' => 'Tag', 'action' => 'created']), 'success');
        return redirect()->route('admin.tags.index');
    }

    public function show(Tag $tag)
    {
        abort_if(!auth()->user()->can('show-tag'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('tags.show', compact('tag'));
    }

    public function edit(Tag $tag)
    {
        abort_if(!auth()->user()->can('update-tag'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tagTypes = ['' => 'Please Select'] + Arr::sort(Tag::TAG_TYPES);

        return view('tags.edit', compact('tag', 'tagTypes'));
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $tag->update($request->validated());

        toast(__('global.crud_actions', ['module' => 'Tag', 'action' => 'updated']), 'success');
        return redirect()->route('admin.tags.index');
    }

    public function destroy(Tag $tag)
    {
        abort_if(!auth()->user()->can('delete-tag'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tag->delete();

        toast(__('global.crud_actions', ['module' => 'Tag', 'action' => 'deleted']), 'success');
        return back();
    }

    public function massDestroy(MassDestroyTagRequest $request)
    {
        Tag::whereIn('id', request('ids'))
            ->update([
                'is_active' => 3,
                'updatedby_userid' => auth()->id(),
            ]);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
