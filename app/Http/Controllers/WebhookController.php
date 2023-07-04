<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class WebhookController extends CashierController
{
    /**
    * Handle a Stripe webhook.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );
        } catch (\Exception $e) {
            return $this->handleWebhookError($e);
        }

        // Handle the specific event type
        if ($event->type === 'checkout.session.completed') {
            // Handle checkout session completed event
            $checkoutSession = $event->data->object;
            // Process the checkout session data as needed
        }

        // Return a response to acknowledge successful webhook handling
        return response()->json(['success' => true]);
    }
}
