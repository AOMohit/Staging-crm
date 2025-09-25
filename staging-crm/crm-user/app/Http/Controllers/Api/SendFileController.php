<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SendFileController extends Controller
{
    //
    function registrationSubmit(Request $request)
    {
        // info(json_encode($request->file('passport_front')));
        $user = User::where('email', $request->email)->first();

        
        if ($request->hasFile('profile')) {
            // dd('hiii');
            @unlink('storage/app/'.$user->profile);
            $user->profile = $request->file('profile')->store('public/image/user');
        }
        if ($request->hasFile('passport_front')) {
            // dd('hiii');
            @unlink('storage/app/'.$user->passport_front);
            $user->passport_front = $request->file('passport_front')->store('public/image/user');
        }
        if ($request->hasFile('passport_back')) {
            // dd('hiii');
            @unlink('storage/app/'.$user->passport_back);
            $user->passport_back = $request->file('passport_back')->store('public/image/user');
        }
        if ($request->hasFile('pan_gst')) {
            // dd('hiii');
            @unlink('storage/app/'.$user->pan_gst);
            $user->pan_gst = $request->file('pan_gst')->store('public/image/user');
        }
        if ($request->hasFile('adhar_card')) {
            // dd('hiii');
            @unlink('storage/app/'.$user->adhar_card);
            $user->adhar_card = $request->file('adhar_card')->store('public/image/user');
        }
        if ($request->hasFile('driving')) {
            // dd('hiii');
            @unlink('storage/app/'.$user->driving);
            $user->driving = $request->file('driving')->store('public/image/user');
        }
        // dd($user->profile);
        $user->save();
        if($user){
            return response([
                'message' => 'File Save Successfully.',
                'success' => true,
                'data' => " ",
            ], 200);
        } 
        
    }
}
