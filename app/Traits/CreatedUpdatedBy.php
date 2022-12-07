<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait will insert/update createdby_userid and updatedby_userid column values automatically, while any create and update event occure
 */
trait CreatedUpdatedBy
{
    public static function bootCreatedUpdatedBy()
    {
        static::creating(function (Model $model) {
            if(Auth()->check()) {
                $model->createdby_userid = auth()->id();
            }
        });

        static::updating(function (Model $model) {
            if(Auth()->check()) {
                $model->updatedby_userid = auth()->id();
            }
        });

        static::deleting(function (Model $model) {
            $model->is_active = 3;
            if(Auth()->check()) {
                $model->updatedby_userid = auth()->id();
            }
        });

        static::deleted(function (Model $model) {
            /**
             * this event is used form updating the is_active and updatedby_userid
             * when model deleted
             */
            if(Auth()->check()) {
                $model->save();
            }
        });
    }
}
