<?php

namespace App\Http\Controllers;

use App\Http\Requests\MassDestroyPricingPlanHistoryRequest;
use Illuminate\Http\Request;
use App\Models\PricingPlanHistory;
use Symfony\Component\HttpFoundation\Response;

class PricingPlanHistoryController extends Controller
{
    public function destroy(PricingPlanHistory $pricingPlanHistory)
    {
        abort_if(!auth()->user()->can('delete-pricing-plan'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pricingPlanHistory->delete();

        toast(__('global.crud_actions', ['module' => 'Pricing Plan History', 'action' => 'deleted']), 'success');
        return back();
    }

    public function massDestroy(MassDestroyPricingPlanHistoryRequest $request)
    {
        PricingPlanHistory::whereIn('id', request('ids'))
            ->update([
                'is_active' => 3,
                'updatedby_userid' => auth()->id(),
            ]);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
