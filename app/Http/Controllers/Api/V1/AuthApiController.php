<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\SendOtpApiRequest;
use App\Http\Requests\Api\VerifyOtpApiRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthApiController extends Controller
{
    public function sendOtp(SendOtpApiRequest $request)
    {
        $isNewUser = false;

        $phoneNumber = $request->user_phone;
        $user = User::where('user_phone', $phoneNumber)->first();

        if(!$user) { // if not exists, create new user
            $isNewUser = true;

            $user = User::create([
                'role_id' => 5, // customer
                'user_phone' => $phoneNumber,
                'user_type'  => 'customer',
                'user_name' => Str::upper(Str::random(4)).rand(11,99).User::count(),
                'is_active' => 1,
            ]);

            // assigning role to user. by default signed up user will be customer
            $user->assignRole(config('app-config.default_signup_role', 'customer'));

            // custom event logging
            activity('user')
                ->by($user)
                ->event('customer-user-created')
                ->withProperties(['status' => 1])
                ->log('customer user created');
        }

        // if user account is not active
        if($user->is_active != 1) {
            $status = '';
            if($user->is_active == 2) {
                $status = trans('api.deactivated');
            } else {
                $status = trans('api.deleted');
            }

            return response()->error('Account '.$status, [
                'account_status' => [trans('api.account_status', ['status' => $status])]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // generate OTP
        $otp = rand(111111, 999999);
        $user->otps()->create([
            'otp' => $otp,
            'otp_valid_from' =>  Carbon::now()->format('Y-m-d H:i:s'),
            'otp_valid_to' =>  Carbon::now()->addMinutes(15)->format('Y-m-d H:i:s'),
        ]);

        // send OTP
        $smsMessage = Str::replaceArray('?', [$otp], config('app-config.sms_format.app_auth_otp'));
        (new \App\Services\SendOtp())->send($user->user_phone, $smsMessage);

        return response()->success(trans('api.auth_otp_sent_successfully'), [
            'user_phone' => (int) $phoneNumber,
            'otp' => $otp,
            'is_new_user' => $isNewUser
        ], Response::HTTP_OK);
    }

    public function verifyOtp(VerifyOtpApiRequest $request)
    {
        $user = User::where('user_phone', $request->user_phone)->first();

        if(!$user) {
            return response()->error(trans('api.account_does_not_exists'), [
                'account_not_exist' => [trans('api.account_does_not_exists')]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // activate user
        $user->update([
            'is_active' => 1,
        ]);

        $otp = Otp::where([
            ['user_id', $user->id],
            ['otp', $request->otp],
            ['is_used', 2],
            ['otp_valid_from', '<=', Carbon::now()->format('Y-m-d H:i:s')],
            ['otp_valid_to', '>=', Carbon::now()->format('Y-m-d H:i:s')],
        ])
        ->first();

        if(!$otp) {
            return response()->error(trans('api.auth_invalid_otp'), [
                'invalid_otp' => [trans('api.auth_invalid_otp')]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // update OTP status
        $otp->is_used = 1;
        $otp->save();

        // generate API token for user
        $token = $user->createToken($request->device_name)->plainTextToken;
        $user->access_token = $token; // adding token to user collection

        // custom event logging
        activity('user')
            ->by($user)
            ->event('otp-validated')
            ->withProperties(['status' => 1])
            ->log('user OTP validated');

        return response()->success(trans('api.logged_in_successfully'), new UserResource($user), Response::HTTP_OK);
    }
}
