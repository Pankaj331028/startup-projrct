<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLanguageRequest;
use App\Models\Language;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Alert;
use App\Http\Requests\MassDestroyLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('read-language'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inputSearchString = $request->input('s', '');

        $languages = collect([]);
        $languages = Language::when($inputSearchString, function($query) use ($inputSearchString) {
                $query->where(function($query) use ($inputSearchString) {
                    $query->orWhere('language_name', 'LIKE', '%'.$inputSearchString.'%')
                        ->orWhere('language_short_code', 'LIKE', '%'.$inputSearchString.'%');
                });
            })
            ->isActive()
            ->orderBy('language_name')
            ->paginate(config('app-config.datatable_default_row_count', 25))
            ->withQueryString();

        return view('languages.index', [
            'languages' => $languages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!auth()->user()->can('create-language'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('languages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLanguageRequest $request)
    {
        Language::create($request->validated());

        toast(__('global.crud_actions', ['module' => 'Language', 'action' => 'created']), 'success');
        return redirect()->route('admin.languages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function show(Language $language)
    {
        abort_if(!auth()->user()->can('show-language'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('languages.show', compact('language'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function edit(Language $language)
    {
        abort_if(!auth()->user()->can('update-language'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('languages.edit', compact('language'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLanguageRequest $request, Language $language)
    {
        $language->update($request->validated());

        toast(__('global.crud_actions', ['module' => 'Language', 'action' => 'updated']), 'success');
        return redirect()->route('admin.languages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function destroy(Language $language)
    {
        abort_if(!auth()->user()->can('delete-language'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $language->update([
            'is_active' => 3,
        ]);

        toast(__('global.crud_actions', ['module' => 'Language', 'action' => 'deleted']), 'success');
        return redirect()->route('admin.languages.index');
    }

    public function massDestroy(MassDestroyLanguageRequest $request)
    {
        Language::whereIn('id', request('ids'))
            ->update([
                'is_active' => 3,
                'updatedby_userid' => auth()->id(),
            ]);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
