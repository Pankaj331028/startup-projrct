<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCountryRequest;
use App\Models\Country;
use App\Models\Language;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Alert;
use App\Http\Requests\MassDestroyCountryRequest;
use App\Http\Requests\UpdateCountryRequest;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('read-country'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inputSearchString = $request->input('s', '');

        $countries = collect([]);
        $countries = Country::with('defaultLanguage:id,language_name,language_short_code')
            ->when($inputSearchString, function($query) use ($inputSearchString) {
                $query->where(function($query) use ($inputSearchString) {
                    $query->orWhere('country_name', 'LIKE', '%'.$inputSearchString.'%')
                        ->orWhere(function($query) use ($inputSearchString) {
                            $query->whereHas('defaultLanguage', function(Builder $builder) use ($inputSearchString) {
                                $builder->where('language_name', 'LIKE', '%'.$inputSearchString.'%')
                                    ->orWhere('language_short_code', 'LIKE', '%'.$inputSearchString.'%')
                                    ->isActive();
                            });
                        });
                });
            })
            ->isActive()
            ->orderBy('country_name')
            ->paginate(config('app-config.datatable_default_row_count', 25))
            ->withQueryString();

        return view('countries.index', [
            'countries' => $countries,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!auth()->user()->can('create-country'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $languages = Language::select('id', 'language_name')
            ->isActive()
            ->orderBy('language_name')
            ->pluck('language_name', 'id')
            ->prepend('Please select', '');

        return view('countries.create', [
            'languages' => $languages
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCountryRequest $request)
    {
        Country::create($request->validated());

        toast(__('global.crud_actions', ['module' => 'Country', 'action' => 'created']), 'success');
        return redirect()->route('admin.countries.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        abort_if(!auth()->user()->can('show-country'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $country->load('defaultLanguage');
        return view('countries.show', compact('country'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        abort_if(!auth()->user()->can('update-country'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $languages = Language::select('id', 'language_name')
            ->isActive()
            ->orderBy('language_name')
            ->pluck('language_name', 'id')
            ->prepend('Please select', '');

        return view('countries.edit', compact('languages', 'country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCountryRequest $request, Country $country)
    {
        $country->update($request->validated());

        toast(__('global.crud_actions', ['module' => 'Country', 'action' => 'updated']), 'success');
        return redirect()->route('admin.countries.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        abort_if(!auth()->user()->can('delete-country'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $country->update([
            'is_active' => 3
        ]);

        toast(__('global.crud_actions', ['module' => 'Country', 'action' => 'deleted']), 'success');
        return redirect()->route('admin.countries.index');
    }

    public function massDestroy(MassDestroyCountryRequest $request)
    {
        Country::whereIn('id', request('ids'))
            ->update([
                'is_active' => 3,
                'updatedby_userid' => auth()->id(),
            ]);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
