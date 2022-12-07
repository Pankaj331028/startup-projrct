<?php

namespace App\Http\Controllers;
use Alert;
use App\Models\Zipcode;
use App\Models\Country;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Requests\StoreZipcodeRequest;
use App\Http\Requests\UpdateZipcodeRequest;
use App\Http\Requests\MassDestroyZipcodeRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ZipcodeController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('read-zipcode'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inputSearchString = $request->input('s', '');
        $zipcodes = Zipcode::select('id','code')
            ->when($inputSearchString, function($query) use ($inputSearchString) {
                $query->where(function($query) use ($inputSearchString) {
                    $query->orWhere('code', 'LIKE', '%'.$inputSearchString.'%');
                });
            })
            ->isActive()
            ->orderBy('code')
            ->paginate(config('app-config.datatable_default_row_count', 25))
            ->withQueryString();

        return view('zipcodes.index', [
            'zipcodes' => $zipcodes,
        ]);
    }

    public function create()
    {
        abort_if(!auth()->user()->can('create-zipcode'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('zipcodes.create');
    }

    public function store(StoreZipcodeRequest $request)
    {
        Zipcode::create($request->validated());

        toast(__('global.crud_actions', ['module' => 'Zipcode', 'action' => 'created']), 'success');
        return redirect()->route('admin.zipcodes.index');
    }

    public function show(Zipcode $zipcode)
    {
        abort_if(!auth()->user()->can('show-zipcode'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('zipcodes.show', compact('zipcode'));
    }

    public function edit(Zipcode $zipcode)
    {
        abort_if(!auth()->user()->can('update-zipcode'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('zipcodes.edit', compact('zipcode'));
    }

    public function update(UpdateZipcodeRequest $request, Zipcode $zipcode)
    {
        $zipcode->update($request->validated());

        toast(__('global.crud_actions', ['module' => 'Zipcode', 'action' => 'updated']), 'success');
        return redirect()->route('admin.zipcodes.index');
    }

    public function destroy(Zipcode $zipcode)
    {
        abort_if(!auth()->user()->can('delete-zipcode'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zipcode->delete();

        toast(__('global.crud_actions', ['module' => 'Zipcode', 'action' => 'deleted']), 'success');
        return back();
    }

    public function massDestroy(MassDestroyZipcodeRequest $request)
    {
        Zipcode::whereIn('id', request('ids'))
            ->update([
                'is_active' => 3,
                'updatedby_userid' => auth()->id(),
            ]);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
