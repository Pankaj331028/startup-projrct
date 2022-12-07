<?php

namespace App\Http\Controllers;
use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Requests\MassDestroyCityRequest;
use App\Models\Country;
use Symfony\Component\HttpFoundation\Response;
use Alert;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;

class CityController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('read-city'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $countries = Country::select('id', 'country_name')
            ->isActive()
            ->orderBy('country_name')
            ->pluck('country_name', 'id')
            ->prepend('All', 'all');

        $inputSearchString = $request->input('s', '');
        $inputCountry = $request->input('country', '');

        $selectedCountries = [];
        $cities = collect([]);
        if(filled($inputSearchString) || filled($inputCountry)) {
            $selectedCountries = $inputCountry ? explode(',', $inputCountry) : [];

            $cities = City::select('id','city_name','country_id')
                ->with('country:id,country_name')
                ->when($selectedCountries, function($query) use ($selectedCountries) {
                    if(in_array('all', $selectedCountries) || in_array(999999, $selectedCountries)) {
                        $query->whereHas('country', function(Builder $builder) {
                            $builder->isActive();
                        });
                    } else {
                        $query->whereIn('country_id', $selectedCountries);
                    }
                })
                ->when($inputSearchString, function($query) use ($inputSearchString) {
                    $query->where(function($query) use ($inputSearchString) {
                        $query->orWhere('city_name', 'LIKE', '%'.$inputSearchString.'%')
                            ->orWhere(function($query) use ($inputSearchString) {
                                $query->whereHas('country', function(Builder $builder) use ($inputSearchString) {
                                    $builder->where('country_name', 'LIKE', '%'.$inputSearchString.'%')->isActive();
                                });
                            });
                    });
                })
                ->isActive()
                ->orderBy('city_name')
                ->paginate(config('app-config.datatable_default_row_count', 25))
                ->withQueryString();
        }

        return view('cities.index', [
            'cities' => $cities,
            'countries' => $countries,
            'selectedCountries' => $selectedCountries
        ]);
    }

    public function create()
    {
        abort_if(!auth()->user()->can('create-city'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $countries = Country::select('id', 'country_name')
            ->isActive()
            ->orderBy('country_name')
            ->pluck('country_name', 'id')
            ->prepend('Please select', '');

        return view('cities.create', compact('countries'));
    }

    public function store(StoreCityRequest $request)
    {
        City::create($request->validated());

        toast(__('global.crud_actions', ['module' => 'City', 'action' => 'created']), 'success');
        return redirect()->route('admin.cities.index');
    }

    public function show(City $city)
    {
        abort_if(!auth()->user()->can('show-city'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $city->load('country:id,country_name');
        return view('cities.show', compact('city'));
    }

    public function edit(City $city)
    {
        abort_if(!auth()->user()->can('update-city'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $countries = Country::select('id', 'country_name')
            ->isActive()
            ->orderBy('country_name')
            ->pluck('country_name', 'id')
            ->prepend('Please select', '');

        return view('cities.edit', compact('city', 'countries'));
    }

    public function update(UpdateCityRequest $request, City $city)
    {
        $city->update($request->validated());

        toast(__('global.crud_actions', ['module' => 'City', 'action' => 'updated']), 'success');
        return redirect()->route('admin.cities.index');
    }

    public function destroy(City $city)
    {
        abort_if(!auth()->user()->can('delete-city'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $city->delete();

        toast(__('global.crud_actions', ['module' => 'City', 'action' => 'deleted']), 'success');
        return back();
    }

    public function massDestroy(MassDestroyCityRequest $request)
    {
        City::whereIn('id', request('ids'))
            ->update([
                'is_active' => 3,
                'updatedby_userid' => auth()->id(),
            ]);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
