<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\V1\Auth\ResetPasswordRequest;
use App\Http\Requests\V1\Auth\ChangePasswordRequest;

class ChangePasswordController extends Controller
{
    public function passwordChange(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $message = __('Old password is wrong.');
            $code = 401;
            return ApiResponse::Error($user, $message, $code);
        }

        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $message = __('Password changed successfully.');
        $code = 200;
        return ApiResponse::Success($user, $message, $code);
    }
}
