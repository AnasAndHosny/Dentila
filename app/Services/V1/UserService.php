<?php

namespace App\Services\V1;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function login($request): array
    {
        $user = User::query()
            ->where('phone_number', $request['phone_number'])
            ->first();
        if (!is_null($user)) {
            if (!Auth::attempt($request->only(['phone_number', 'password']))) {
                $user = null;
                $message = __('Phone number & password does not match with our record.');
                $code = 401;
            } else {
                $user['token'] = $user->createToken('token')->plainTextToken;
                $message = __(key: 'User logged in successfully.');
                $code = 200;
            }
        } else {
            $message = __('Phone number & password does not match with our record.');
            $code = 401;
        }
        return ['data' => $user, 'message' => $message, 'code' => $code];
    }

    public function logout(): array
    {
        $user = Auth::user();
        if (!is_null($user)) {
            $user->currentAccessToken()->delete();

            $message = __('User logged out successfully.');
            $code = 200;
        } else {
            $message = __('invalid token.');
            $code = 404;
        }
        return ['data' => $user, 'message' => $message, 'code' => $code];
    }
}
