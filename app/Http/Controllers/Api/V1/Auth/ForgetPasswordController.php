<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Helpers\ApiResponse;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\ForgetPasswordRequest;
use App\Notifications\ResetPasswordVerificationNotification;
use App\Notifications\ResetPasswordWhatsAppNotification;

class ForgetPasswordController extends Controller
{
    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $user = User::where('phone_number', $request->phone_number)->first();
        $user->notify(new ResetPasswordWhatsAppNotification());

        $data = [
            'phone_number' => $request->phone_number
        ];
        $message = __('The resetting password code has been sent successfully to your phone number.');
        $code = 200;
        return ApiResponse::Success($data, $message, $code);
    }
}
