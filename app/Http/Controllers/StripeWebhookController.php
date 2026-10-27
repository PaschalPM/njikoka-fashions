<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                (string) $request->header('Stripe-Signature'),
                (string) config('services.stripe.webhook_secret'),
            );
        } catch (UnexpectedValueException|SignatureVerificationException) {
            return response('Invalid payload', 400);
        }

        if (in_array($event->type, ['checkout.session.completed', 'checkout.session.async_payment_succeeded'])) {
            $session = $event->data->object;

            if ($session->payment_status === 'paid') {
                Order::where('stripe_session_id', $session->id)->first()?->markPaid($session->payment_intent);
            }
        }

        return response('OK');
    }
}
