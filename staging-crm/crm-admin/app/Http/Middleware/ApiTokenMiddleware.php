<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
   public function handle($request, Closure $next)
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader) {
            return response()->json(['error' => 'No token provided'], 401);
        }

        $token = str_replace('Bearer ', '', $authHeader);
        $storedToken = Crypt::decryptString(env('API_TOKEN'));

        if ($token !== $storedToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
