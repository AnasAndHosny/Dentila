<?php

namespace App\Http\Controllers\Api\V1;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PaymentIntent as PaymentIntentModel;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        $patientAccountId = auth()->user()->patient->account->id;
        $request->validate([
            'amount' => ['required', 'integer', 'min:50'],
            'currency' => ['required', 'string', 'in:usd,eur,egp'],
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // ننشئ PaymentIntent في Stripe
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount * 100,
                'currency' => $request->currency,
                'metadata' => [
                    'patient_account_id' => $patientAccountId,
                ],
            ]);

            // نخزن نسخة منه في قاعدة بياناتنا
            $localIntent = PaymentIntentModel::create([
                'patient_account_id' => $patientAccountId,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'status' => 'pending',
            ]);

            $data = [
                'client_secret' => $paymentIntent->client_secret,
            ];
            $message = __('messages.store_success', ['class' => __('client_secret')]);
            $code = 201;
            return ApiResponse::Success($data, $message, $code);
        } catch (\Exception $e) {
            return ApiResponse::Error([], $e->getMessage(), 400);
        }
    }
}
