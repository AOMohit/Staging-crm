<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        // dd('hiii');
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $checkRedirection = auth()->user()->is_password_changed;

        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'is_password_changed'=> 1
        ]);
        if($checkRedirection == 0){
            return redirect()->route('dashboard')->with('message', 'Password Updated Successfully');
        }
        return back()->with('message', 'Password Updated Successfully');
    }
}