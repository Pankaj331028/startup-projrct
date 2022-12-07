<?php

namespace App\Http\Controllers;
use App\Models\PricingPlanDetail;
use Illuminate\Http\Request;
use App\Http\Requests\MassDestroyPricingPlanDetailRequest;
use App\Models\Country;
use Symfony\Component\HttpFoundation\Response;
use Alert;
use App\Http\Requests\StorePricingPlanDetailRequest;
use App\Http\Requests\UpdatePricingPlanDetailRequest;
use App\Models\PricingPlan;
use Illuminate\Contracts\Database\Eloquent\Builder;

class PricingPlanDetailController extends Controller
{
    public function index(Request $request, PricingPlan $pricingPlan)
    {
        abort_if(!auth()->user()->can('read-pricing-plan'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inputSearchString = $request->input('s', '');

        $pricingPlanDetails = $pricingPlan->pricingPlanDetails()
            ->when($inputSearchString, function($query) use ($inputSearchString) {
                $query->where(function($query) use ($inputSearchString) {
                    $query->orWhere('pricing_plan_keyname', 'LIKE', '%'.$inputSearchString.'%')
                        ->orWhere('pricing_plan_value', 'LIKE', '%'.$inputSearchString.'%');
                });
            })
            ->isActive()
            ->orderBy('pricing_plan_keyname')
            ->paginate(config('app-config.datatable_default_row_count', 25))
            ->withQueryString();

        return view('pricing-plan-details.index', [
            'pricingPlan' => $pricingPlan,
            'pricingPlanDetails' => $pricingPlanDetails,
        ]);
    }

    public function create(PricingPlan $pricingPlan)
    {
        abort_if(!auth()->user()->can('create-pricing-plan'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('pricing-plan-details.create', compact('pricingPlan'));
    }

    public function store(StorePricingPlanDetailRequest $request, PricingPlan $pricingPlan)
    {
        $pricingPlan->pricingPlanDetails()->updateOrCreate([
            'pricing_plan_keyname' => $request->pricing_plan_keyname,
            'is_active' => 1,
        ], $request->safe()->only('pricing_plan_value', 'valid_from', 'valid_to'));

        toast(__('global.crud_actions', ['module' => 'Pricing Plan Details', 'action' => 'created']), 'success');
        return redirect()->route('admin.pricing-plans.pricing-plan-details.index', ['pricing_plan' => $pricingPlan]);
    }

    /* public function show(PricingPlan $pricingPlan, PricingPlanDetail $pricingPlanDetail)
    {
        abort_if(!auth()->user()->can('show-pricing-plan'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('pricing-plan-details.show', compact('pricingPlan', 'pricingPlanDetail'));
    } */

    public function edit(PricingPlan $pricingPlan, PricingPlanDetail $pricingPlanDetail)
    {
        abort_if(!auth()->user()->can('update-pricing-plan'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('pricing-plan-details.edit', compact('pricingPlan', 'pricingPlanDetail'));
    }

    public function update(UpdatePricingPlanDetailRequest $request, PricingPlan $pricingPlan, PricingPlanDetail $pricingPlanDetail)
    {
        $pricingPlanDetail->updateOrCreate([
            'pricing_plan_keyname' => $request->pricing_plan_keyname,
            'is_active' => 1,
        ], $request->safe()->only('pricing_plan_value', 'valid_from', 'valid_to'));

        toast(__('global.crud_actions', ['module' => 'Pricing Plan Details', 'action' => 'updated']), 'success');
        return redirect()->route('admin.pricing-plans.pricing-plan-details.index', ['pricing_plan' => $pricingPlan]);
    }

    public function destroy(PricingPlan $pricingPlan, PricingPlanDetail $pricingPlanDetail)
    {
        abort_if(!auth()->user()->can('delete-pricing-plan'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pricingPlanDetail->delete();

        toast(__('global.crud_actions', ['module' => 'Pricing Plan Detail', 'action' => 'deleted']), 'success');
        return back();
    }

    public function massDestroy(MassDestroyPricingPlanDetailRequest $request, PricingPlan $pricingPlan, PricingPlanDetail $pricingPlanDetail)
    {
        PricingPlanDetail::whereIn('id', request('ids'))
            ->update([
                'is_active' => 3,
                'updatedby_userid' => auth()->id(),
            ]);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
