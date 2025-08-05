<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use Ichtrojan\Otp\Otp;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\ResetPasswordRequest;

class ResetPasswordController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp;
    }

    public function passwordReset(ResetPasswordRequest $request)
    {
        $otp = $this->otp->validate($request->phone_number . '|reset', $request->otp);

        $data = [
            'phone_number' => $request->phone_number
        ];

        if (!$otp->status) {
            return ApiResponse::Error($data, __($otp->message), 400);
        }

        $user = User::where('phone_number', $request->phone_number)->first();
        $user->update(['password' => $request->password]);
        $user->tokens()->delete();

        $message = __('Your password has been reset successfully.');
        $code = 200;
        return ApiResponse::Success($data, $message, $code);
    }
}
