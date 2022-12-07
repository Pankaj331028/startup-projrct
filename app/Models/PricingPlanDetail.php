<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use App\Traits\ModelScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PricingPlanDetail extends Model
{
    use HasFactory, LogsActivity;
    use CreatedUpdatedBy, ModelScopes;

    protected $guarded = [];

    public const PRICING_PLAN_KEYNAMES = [
        'amount' => 'Amount',
        'tax' => 'Tax',
        'total_amount' => 'Total amount',
    ];

    /** Spatie Activity Log */
    public function getActivitylogOptions(): LogOptions // spatie model log options
    {
        return LogOptions::defaults()->logAll()->useLogName('PricingPlanDetail');
    }

    /** Relationships */
    public function pricingPlan()
    {
        return $this->belongsTo(PricingPlan::class);
    }
}
