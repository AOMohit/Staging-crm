<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TokenController extends Controller
{

    public function getToken(Request $request)
    {
        // $clientIp = $request->ip();
        // $serverIp = $_SERVER['SERVER_ADDR'];
        // $allowedIps = explode(',', env('ALLOWED_CMS_IPS'));
        // // $domain = "adventuresoverland.com";
        // $domain = $request->getHost();
        // $ipv4 = gethostbyname($domain);
        // $dnsRecords = dns_get_record($domain, DNS_AAAA);
        // $ipv6 = $dnsRecords[0]['ipv6'] ?? null;
        $providedToken = $request->bearerToken();
        $expectedToken = env('API_SECRET_KEY');

        if ($providedToken !== $expectedToken) {
            return response()->json(['error' => 'Unauthorized request'], 401);
        }

        $token = Crypt::decryptString(env('API_TOKEN'));

        return response()->json(['token' => $token]);
    }
}
