<?php

namespace App\Http\Controllers;
use App\Models\PricingPlan;
use Illuminate\Http\Request;
use App\Http\Requests\MassDestroyPricingPlanRequest;
use App\Models\Country;
use Symfony\Component\HttpFoundation\Response;
use Alert;
use App\Http\Requests\StorePricingPlanRequest;
use App\Http\Requests\UpdatePricingPlanRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PricingPlanController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('read-pricing-plan'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inputSearchString = $request->input('s', '');

        $pricingPlans = PricingPlan::with('planLatestPrice')
            ->when($inputSearchString, function($query) use ($inputSearchString) {
                $query->where(function($query) use ($inputSearchString) {
                    $query->orWhere('pricing_plan_name', 'LIKE', '%'.$inputSearchString.'%')
                        ->orWhere('pricing_plan_type', 'LIKE', '%'.$inputSearchString.'%')
                        ->orWhere('pricing_plan_payment_frequency', 'LIKE', '%'.$inputSearchString.'%');
                });
            })
            ->isActive()
            ->orderBy('pricing_plan_name')
            ->paginate(config('app-config.datatable_default_row_count', 25))
            ->withQueryString();

        return view('pricing-plans.index', [
            'pricingPlans' => $pricingPlans,
        ]);
    }

    public function create()
    {
        abort_if(!auth()->user()->can('create-pricing-plan'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('pricing-plans.create');
    }

    public function store(StorePricingPlanRequest $request)
    {
        DB::transaction(function () use ($request) {
            $pricingPlan = PricingPlan::create($request->safe()->except('amount', 'tax', 'valid_from', 'valid_to'));

            $pricingPlan->pricingPlanHistories()->create([
                'amount' => formatNumberAsPrice($request->amount),
                'tax' => formatNumberAsPrice($request->tax),
                'total_amount' => formatNumberAsPrice($request->amount + ( ($request->amount * $request->tax) / 100 )),
                'valid_from' => $request->valid_from,
                'valid_to' => $request->valid_to,
            ]);
        });

        toast(__('global.crud_actions', ['module' => 'Pricing Plan', 'action' => 'created']), 'success');
        return redirect()->route('admin.pricing-plans.index');
    }

    public function show(PricingPlan $pricingPlan)
    {
        $pricingPlan->load([
            'pricingPlanHistories' => function($query) {
                $query->isActive()->orderBy('valid_from', 'ASC');
            },
            'planLatestPrice'
        ]);
        abort_if(!auth()->user()->can('show-pricing-plan'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('pricing-plans.show', compact('pricingPlan'));
    }

    public function edit(PricingPlan $pricingPlan)
    {
        abort_if(!auth()->user()->can('update-pricing-plan'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pricingPlan->load('planLatestPrice');
        return view('pricing-plans.edit', compact('pricingPlan'));
    }

    public function update(UpdatePricingPlanRequest $request, PricingPlan $pricingPlan)
    {
        DB::transaction(function () use ($request, $pricingPlan) {
            $pricingPlan->update($request->safe()->except('amount', 'tax', 'valid_from', 'valid_to'));

            // if there is change in amount, tax, valid_from, and valid_to then insert new row in history
            $planLatestPrice = $pricingPlan->planLatestPrice;
            if((
                $planLatestPrice->amount != $request->amount) ||
                ($planLatestPrice->tax != $request->tax) ||
                ($planLatestPrice->valid_from != $request->valid_from) ||
                ($planLatestPrice->valid_to != $request->valid_to)
            ) {
                $pricingPlan->pricingPlanHistories()->create([
                    'amount' => formatNumberAsPrice($request->amount),
                    'tax' => formatNumberAsPrice($request->tax),
                    'total_amount' => formatNumberAsPrice($request->amount + ( ($request->amount * $request->tax) / 100 )),
                    'valid_from' => $request->valid_from,
                    'valid_to' => $request->valid_to,
                ]);
            }
        });

        toast(__('global.crud_actions', ['module' => 'Pricing Plan', 'action' => 'updated']), 'success');
        return redirect()->route('admin.pricing-plans.index');
    }

    public function destroy(PricingPlan $pricingPlan)
    {
        abort_if(!auth()->user()->can('delete-pricing-plan'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pricingPlan->delete();

        toast(__('global.crud_actions', ['module' => 'Pricing Plan', 'action' => 'deleted']), 'success');
        return back();
    }

    public function massDestroy(MassDestroyPricingPlanRequest $request)
    {
        PricingPlan::whereIn('id', request('ids'))
            ->update([
                'is_active' => 3,
                'updatedby_userid' => auth()->id(),
            ]);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
