<?php

namespace App\Http\Controllers\Api\V1;

use Stripe\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\PaymentIntent as PaymentIntentModel;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload     = $request->getContent();
        $sigHeader   = $request->header('Stripe-Signature');
        $endpointSec = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSec);

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $intentObj = $event->data->object;
                    $this->markSucceeded($intentObj);
                    break;

                case 'payment_intent.payment_failed':
                    $intentObj = $event->data->object;
                    $this->markFailed($intentObj);
                    break;

                case 'payment_intent.canceled':
                    $intentObj = $event->data->object;
                    $this->markCanceled($intentObj);
                    break;

                default:
                    Log::info('Unhandled event: ' . $event->type);
            }

            return response()->json(['status' => 'ok']);
        } catch (SignatureVerificationException $e) {
            Log::error('Invalid webhook signature');
            return response()->json(['message' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['message' => 'Webhook error'], 500);
        }
    }

    private function markSucceeded($intentObj): void
    {
        $local = PaymentIntentModel::where('stripe_payment_intent_id', $intentObj->id)->first();
        if (! $local) return;

        // حماية من التكرار (idempotent): طبق الخصم مرة واحدة فقط
        if ($local->status !== 'succeeded') {
            $local->update(['status' => 'succeeded']);

            $amountReceived = $intentObj->amount_received ?? $intentObj->amount ?? 0;
            $local->patientAccount->applyTransaction(
                type: 'credit',
                amount: $amountReceived,
                treatmentId: null,
                note: 'دفع الكتروني',
                method: 'electronic'
            );
        }
        Log::info('✅ Payment succeeded ' . $intentObj->id);
    }

    private function markFailed($intentObj): void
    {
        PaymentIntentModel::where('stripe_payment_intent_id', $intentObj->id)
            ->where('status', '!=', 'succeeded') // لا تغيّر لو كانت نجحت
            ->update(['status' => 'failed']);

        Log::warning('❌ Payment failed ' . $intentObj->id);
    }

    private function markCanceled($intentObj): void
    {
        PaymentIntentModel::where('stripe_payment_intent_id', $intentObj->id)
            ->where('status', '!=', 'succeeded') // لا تغيّر لو كانت نجحت
            ->update(['status' => 'canceled']);

        Log::info('🛑 Payment canceled ' . $intentObj->id);
    }
}
