<?php

namespace App\Models;

use App\Models\Country;
use App\Traits\ModelScopes;
use App\Traits\CreatedUpdatedBy;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use HasFactory, LogsActivity;
    use CreatedUpdatedBy, ModelScopes;

    protected $guarded = [];

    /** Spatie Activity Log */
    public function getActivitylogOptions(): LogOptions // spatie model log options
    {
        return LogOptions::defaults()->logAll()->useLogName('City');
    }

    /** Relationships */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
