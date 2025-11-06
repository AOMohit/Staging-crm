<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Show the form to request a password reset link.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send the password reset link.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        // dd($request->validate(['email' => 'required|email']));
        // saleem123@mailinator.com
        // $2y$10$Yc62zA10iJ2zGQyJGd/unOFBgDjJ3B7ar23WkHuaT86If.KfH6GBK
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Return response accordingly
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
}
