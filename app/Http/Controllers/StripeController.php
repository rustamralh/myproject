<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\Product;

class StripeController extends Controller
{
    public function getProducts()
    {
        // Set your Stripe API key
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $products = collect();
        try {
            // Retrieve products from Stripe
            $products = Product::all();


            return Inertia::render('Stripe/Products', ['products' => $products]);

            // Return a response or perform further actions
            return response()->json(['message' => 'Products retrieved successfully']);
        } catch (\Exception $e) {
            // Handle any exceptions that occur
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createPaymentMethod()
    {
        return Inertia::render('Stripe/PaymentMethod');
    }


    public function getSubscriptionLink(Request $request)
    {
        $user = User::findOrFail(1);
        // $user = $request->user();
        $user->newSubscription('card', $request->product_id)->create();
        return redirect('/payment/success');
    }
}
