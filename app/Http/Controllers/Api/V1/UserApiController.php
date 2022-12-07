<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\UpdateUserApiRequest;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class UserApiController extends Controller
{
    public function show()
    {
        abort_if(!auth()->user()->can('show-profile'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new UserResource(auth()->user());
    }

    public function update(UpdateUserApiRequest $request)
    {
        [$keys, $values] = Arr::divide($request->validated());
        auth()->user()->update($request->validated());

        return response()->success('Success', new UserResource(auth()->user()), Response::HTTP_ACCEPTED);
    }
}
