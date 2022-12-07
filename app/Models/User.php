<?php

namespace App\Models;

use App\Traits\ModelScopes;
use Spatie\Image\Manipulations;
use App\Traits\CreatedUpdatedBy;
use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity, HasRoles, InteractsWithMedia;
    use CreatedUpdatedBy, ModelScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $fillable  = [
        'role_id',
        'name',
        'first_name',
        'last_name',
        'email',
        'user_phone',
        'user_type',
        'user_name',
        'email_verified_at',
        'password',
        'birthday',
        'gender',
        'invited_by_user_id',
        'is_verified',
        'verifiedby_userid',
        'verified_on',
        'about_the_user',
        'intro',
        'health_information_title',
        'health_information_details',
        'health_information_date',
        'health_information_info_url',
        'user_status',
        'is_active',
        'status',
        'createdby_userid',
        'updatedby_userid',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Spatie Activity Log
     */
    public function getActivitylogOptions(): LogOptions // spatie model log options
    {
        return LogOptions::defaults()->logAll()->useLogName('User');
    }

    /**
     * for spatie media management
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('User')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('preview')->fit(Manipulations::FIT_CROP, 300, 300)->nonQueued();
                $this->addMediaConversion('thumb')->fit(Manipulations::FIT_CROP, 50, 50)->nonQueued();
            });

        $this->addMediaCollection('profile_photo')
            ->onlyKeepLatest(6)
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('preview')->fit(Manipulations::FIT_CROP, 300, 300)->nonQueued();
                $this->addMediaConversion('thumb')->fit(Manipulations::FIT_CROP, 50, 50)->nonQueued();
            });
    }

    public function birthday(): Attribute
    {
        return new Attribute(
            get: fn ($value) => Carbon::parse($value)->format(config('app-config.date_format.date')),
        );
    }

    // #TODO #LCNOTE enable this. somehow on API login, this is causing issue
    public function createdAt(): Attribute
    {
        /* return new Attribute(
            get: fn ($value) => Carbon::parse($value)->format(config('app-config.date_format.date').' '.config('app-config.date_format.time')),
        ); */

        return new Attribute(
            get: fn ($value) => $value,
        );
    }

    public function userProfileMedia()
    {
        $medias = $this->getMedia('profile_photo');

        $returnMedias = [];
        foreach($medias as $media ) {
            $returnMedia = [
                'id' => $media->id,
                'original_url' => $media->original_url,
                'preview_url' => $media->preview_url,
                'thumb_url' => $media->getUrl('thumb'),
                'name' => $media->name,
                'file_name' => $media->file_name,
                'extension' => $media->extension,
                'size' => $media->size,
            ];
            array_push($returnMedias, $returnMedia);
        }
        return $returnMedias;
    }

    public function fullName()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /** Relationship */
    public function otps()
    {
        return $this->hasMany(Otp::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges', 'user_id', 'badge_id')
            ->withPivot('is_active', 'status', 'createdby_userid', 'updatedby_userid')
            ->withTimestamps();;
    }

    public function userInformations()
    {
        return $this->hasMany(UserInformation::class);
    }

    public function userLocationDetails()
    {
        return $this->hasMany(UserLocationDetail::class);
    }

    public function userLiveLocationDetails()
    {
        return $this->hasMany(userLiveLocationDetail::class);
    }

    public function userLatestLiveLocationDetail()
    {
        return $this->hasOne(userLiveLocationDetail::class)->latestOfMany();
    }

    public function userSearchFactors()
    {
        return $this->hasMany(UserSearchFactor::class);
    }

    public function userInterests()
    {
        return $this->hasMany(UserInterest::class);
    }

    public function userMatches()
    {
        return $this->hasMany(UserMatch::class);
    }

    public function userActivities()
    {
        return $this->hasMany(UserActivity::class);
    }

    public function userGameMinisterSurveyResponses()
    {
        return $this->hasMany(UserGameMinisterSurveyResponse::class);
    }
}
