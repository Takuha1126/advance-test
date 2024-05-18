<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use App\Models\Payment;


use App\Models\Reservation;

class PaymentController extends Controller
{
    
    public function showPaymentPage($reservationId)

{
    $reservation = Reservation::findOrFail($reservationId);

    $payment = Payment::where('reservation_id', $reservationId)->first();

    if ($payment) {
        return back()->with('error_message', 'この予約は既に支払い済みです。');
    }

    try {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $paymentIntent = PaymentIntent::create([
            'amount' => 2000,
            'currency' => 'jpy',
            'payment_method_types' => ['card'],
        ]);

        return view('payment', [
            'stripeKey' => env('STRIPE_PUBLIC_KEY'),
            'paymentIntent' => $paymentIntent,
            'reservationId' => $reservationId,
        ]);

    } catch (\Exception $e) {
        return back()->with('error_message', '支払いページを表示できませんでした。');
    }
}



    public function handlePayment(Request $request)
{
    Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

    try {
        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount,
            'currency' => 'jpy',
            'payment_method_types' => ['card'],
        ]);

        $reservation = Reservation::lockForUpdate()->findOrFail($request->reservation_id);

        if ($reservation->paid) {
            return response()->json(['error' => 'この予約は既に支払い済みです。'], 400);
        }

        Payment::create([
            'reservation_id' => $request->reservation_id,
            'paid_at' => now()
        ]);

        $reservation->update(['paid' => true]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
}

}
