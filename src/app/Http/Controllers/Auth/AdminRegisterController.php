<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Http\Requests\AdminRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('admin.register');
    }

    public function register(AdminRequest $request)
    {
        $admin = Admin::create([
            'admin_name' => $request->admin_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $admin->sendEmailVerificationNotification();

        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.verify');
    }

    public function showVerifyForm()
    {
        return view('admin.verify');
    }

    public function resendVerificationEmail()
    {
        $admin = Auth::guard('admin')->user();
        $admin->sendEmailVerificationNotification();

        return redirect()->route('admin.verify')->with('resent', true);
    }

}

