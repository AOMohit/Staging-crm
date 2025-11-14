<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class RolePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()->role_id == 0){
            return $next($request);
        } else{
            
            $user = User::find(Auth::user()->id);
            $check = $user->role->permission;
            
            if($check->admin == 1){
                return $next($request);
            }else{
                $grpName = request()->route()->action['prefix'];
                $grpName = explode("/",$grpName);
                $grpName = end($grpName);
                // info($grpName);
                $per = $check->toArray();
                foreach($per as $key=>$val){
                    if($key == $grpName && $val == 1){
                        return $next($request);
                    }
                }
                return redirect()->route('dashboard');
            }
        }
        return redirect()->route('dashboard');
    }
}