<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class UserProfilePhotoApiController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->can('show-profile'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return response()->success(trans('api.model_details', ['model' => 'User profile photo']), auth()->user()->userProfileMedia(), Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('update-profile'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->hasFile('profile_photos')) {
            $fileAdders = auth()->user()->addMultipleMediaFromRequest(['profile_photos'])->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('profile_photo');
            });
        }

        return response()->success(trans('api.crud_activity', ['model' => 'Profile photos', 'action' => 'uploaded']), [], Response::HTTP_CREATED);
    }

    public function destroy(Media $userProfilePhoto)
    {
        abort_if(!auth()->user()->can('update-profile'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userProfilePhoto->delete();

        return response()->success(trans('api.crud_activity', ['model' => 'Profile photo', 'action' => 'deleted']), [], Response::HTTP_ACCEPTED);
    }
}
