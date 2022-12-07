<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use App\Traits\ModelScopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PricingPlan extends Model
{
    use HasFactory, LogsActivity;
    use CreatedUpdatedBy, ModelScopes;

    protected $guarded = [];

    public const PLAN_TYPE = [
        'one-time' => 'One time',
        'subscription' => 'Subscription',
    ];

    public const PLAN_PAYMENT_FREQUENCY = [
        'one-time' => 'One time',
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
    ];

    /** Spatie Activity Log */
    public function getActivitylogOptions(): LogOptions // spatie model log options
    {
        return LogOptions::defaults()->logAll()->useLogName('PricingPlan');
    }

    /** relationships */
    public function pricingPlanDetails()
    {
        return $this->hasMany(PricingPlanDetail::class);
    }

    public function pricingPlanHistories()
    {
        return $this->hasMany(PricingPlanHistory::class);
    }

    public function planLatestPrice()
    {
        return $this->hasOne(PricingPlanHistory::class)
            ->where([
                ['valid_from', '<=', Carbon::now()->format('Y-m-d H:i:s')],
                ['valid_to', '>=', Carbon::now()->format('Y-m-d H:i:s')],
            ])
            ->latestOfMany()
            ->withDefault([ // return default values if plan price does not exist in database
                'amount' => formatNumberAsPrice(config('app-config.default_plan_price.amount')),
                'tax' => formatNumberAsPrice(config('app-config.default_plan_price.tax')),
                'total_amount' => formatNumberAsPrice(config('app-config.default_plan_price.total_amount')),
                'valid_from' => Carbon::now()->format('Y-m-d H:i:s'),
                'valid_to' => Carbon::now()->addMinutes(10)->format('Y-m-d H:i:s'),
            ]);
    }
}
