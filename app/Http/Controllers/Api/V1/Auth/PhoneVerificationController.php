<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Ichtrojan\Otp\Otp;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Notifications\EmailVerificationNotification;
use App\Http\Requests\V1\Auth\PhoneNumberVerificationRequest;
use App\Notifications\PhoneVerificationNotification;

class PhoneVerificationController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp;
    }

    public function sendPhoneVerification(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedPhone()) {
            $message = __('Your phone number is already verified.');
            $code = 400;
            return ApiResponse::Error($user, $message, 400);
        }


        $user->notify(new PhoneVerificationNotification());
        $message = __('Phone number verification code sent successfully.');
        $code = 200;
        return ApiResponse::Success($user, $message, $code);
    }

    public function phoneVerification(PhoneNumberVerificationRequest $request): JsonResponse
    {
        $user = $request->user();
        $otp = $this->otp->validate($user->phone_number . '|verify', $request->otp);

        if (!$otp->status) {
            return ApiResponse::Error($user, __($otp->message), 400);
        }

        $user->markPhoneAsVerified();

        $message = __('Your phone number has been verified successfully.');
        $code = 200;
        return ApiResponse::Success($user, $message, $code);
    }
}
