<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponse;
use App\Services\V1\Otp;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\CheckOtpRequest;

class OtpController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp;
    }

    public function check(CheckOtpRequest $request): JsonResponse
    {
        $otp = $this->otp->check($request->phone_number . '|reset', $request->otp);

        $data = [
            'phone_number' => $request->phone_number
        ];

        $code = $otp->status ? 200 : 400;
        $message = __($otp->message);

        if ($code == 400) return ApiResponse::Error($data, $message, $code);

        return ApiResponse::Success($data, $message, $code);
    }
}
