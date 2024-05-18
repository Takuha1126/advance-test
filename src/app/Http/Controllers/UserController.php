<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
    * ユーザーのマイページを表示する
    *
    * @return \Illuminate\View\View
    */
    public function mypage(){
    $user = Auth::user();
    $favorites = $user->favorites;
    $reservations = $user->reservations;

    $errorMessages = [];

    foreach ($reservations as $reservation) {

        $payment = $reservation->payment;

        if ($payment) {
            $errorMessages[$reservation->id] = 'この予約は既に支払い済みです。';
        }
    }

    return view('mypage', compact('reservations', 'favorites', 'errorMessages'));
}

}



