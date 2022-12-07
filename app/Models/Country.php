<?php

namespace App\Models;

use App\Models\City;
use App\Traits\ModelScopes;
use App\Traits\CreatedUpdatedBy;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory, LogsActivity;
    use CreatedUpdatedBy, ModelScopes;

    protected $guarded = [];

    /** Spatie Activity Log */
    public function getActivitylogOptions(): LogOptions // spatie model log options
    {
        return LogOptions::defaults()->logAll()->useLogName('Country');
    }

    /** Relationship */
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function defaultLanguage()
    {
        return $this->belongsTo(Language::class, 'default_language_id')->withDefault([
            'language_name' => 'n/a',
            'language_short_code' => 'n/a',
        ]);
    }
}
