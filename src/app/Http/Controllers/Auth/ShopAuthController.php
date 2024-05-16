<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\ShopRepresentative;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShopLoginRequest;
use App\Models\Shop;

class ShopAuthController extends Controller
{
    public function showLoginForm()
    {
        $shops = Shop::all();
        return view('shops.login', compact('shops'));
    }

    public function login(ShopLoginRequest $request)
    {
        $representative = ShopRepresentative::where('shop_id', $request->shop_id)->first();

        if ($representative && Hash::check($request->password, $representative->password)) {
            Auth::guard('shop')->login($representative);
            return redirect()->route('shops.reservations.list', ['shop_id' => $request->shop_id]);
        }

        return back()->with('error_message', '店舗IDまたはパスワードが正しくありません');
    }

    public function logout()
    {
        Auth::guard('shop')->logout();
        return redirect('/shop/login');
    }
}
