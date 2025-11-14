<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarbonDonation;

class CarbonDonationController extends Controller
{
    public function show()
    {
        return view('admin.carbon.form');
    }

    public function payu(Request $request)
    {
        $request->donation = 1;
        $request->validate([
            'name'     => 'required|string|max:190',
            'email'    => 'required|email|max:190',
            'mobile'   => 'required|string|max:20',
            'donation' => 'required|numeric|min:1',
            'pan_card' => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'co2'      => 'nullable|numeric',
            'trees'    => 'nullable|integer',
        ]);

        $txnid = 'TXN' . time() . rand(1000,9999);

        $donation = CarbonDonation::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'mobile'   => $request->mobile,
            'pan_card' => $request->pan_card,
            'address'  => $request->address,
            'co2'      => $request->co2,
            'trees'    => $request->trees,
            'donation' => $request->donation,
            'status'   => 'pending',
            'gateway_txn_id' => $txnid,
        ]);

        $key      = env('PAYU_KEY');
        $salt     = env('PAYU_SALT');
        $base_url = rtrim(env('PAYU_BASE_URL'), '/'); // e.g. https://secure.payu.in or https://test.payu.in

        $amount      = number_format((float)$request->donation, 2, '.', ''); 
        $productinfo = 'Carbon Offset Donation';
        $firstname   = $request->name;
        $email       = $request->email;
        $phone       = $request->mobile;

        $udf1 = (string)$donation->id;
        $udf2 = $udf3 = $udf4 = $udf5 = $udf6 = $udf7 = $udf8 = $udf9 = $udf10 = '';

        $hashSeq = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
        $dataForHash = [
            'key'         => $key,
            'txnid'       => $txnid,
            'amount'      => $amount,
            'productinfo' => $productinfo,
            'firstname'   => $firstname,
            'email'       => $email,
            'udf1'        => $udf1,
            'udf2'        => $udf2,
            'udf3'        => $udf3,
            'udf4'        => $udf4,
            'udf5'        => $udf5,
            'udf6'        => $udf6,
            'udf7'        => $udf7,
            'udf8'        => $udf8,
            'udf9'        => $udf9,
            'udf10'       => $udf10,
        ];

        $hash_string = '';
        foreach (explode('|', $hashSeq) as $k) {
            $hash_string .= ($dataForHash[$k] ?? '') . '|';
        }
        $hash_string .= $salt;
        $hash = strtolower(hash('sha512', $hash_string));

        $callbackBase = rtrim(env('PAYU_CALLBACK_BASE'), '/');
        $surl = $callbackBase . route('carbon.payu-response', [], false);
        $furl = $callbackBase . route('carbon.payu-response', [], false); 

        return view('admin.carbon.payu-iframe', compact(
            'key', 'txnid', 'amount', 'productinfo', 'firstname', 'email', 'phone',
            'hash', 'base_url', 'udf1', 'udf2', 'udf3', 'udf4', 'udf5', 'udf6', 'udf7', 'udf8', 'udf9', 'udf10',
            'surl', 'furl'
        ));
    }

    public function payuResponse(Request $request)
    {
        if ($request->isMethod('get')) {
            return 'Callback endpoint is up (awaiting POST from PayU).';
        }
        \Log::info('PayU callback (raw)', $request->all());

        $key   = env('PAYU_KEY');
        $salt  = env('PAYU_SALT');
        $p     = $request->all();

        if (empty($p)) return "No response from PayU";

         \Log::info('PayU callback (key fields)', [
            'status' => $p['status'] ?? null,
            'txnid'  => $p['txnid'] ?? null,
            'amount' => $p['amount'] ?? null,
            'udf1'   => $p['udf1'] ?? null,
        ]);

        $status      = $p['status']       ?? '';
        $firstname   = $p['firstname']    ?? '';
        $amount      = $p['amount']       ?? '0.00';
        $txnid       = $p['txnid']        ?? '';
        $posted_hash = $p['hash']         ?? '';
        $email       = $p['email']        ?? '';
        $productinfo = $p['productinfo']  ?? '';
        $udf1        = $p['udf1']         ?? '';
        $udf2        = $p['udf2']         ?? '';
        $udf3        = $p['udf3']         ?? '';
        $udf4        = $p['udf4']         ?? '';
        $udf5        = $p['udf5']         ?? '';
        $udf6        = $p['udf6']         ?? '';
        $udf7        = $p['udf7']         ?? '';
        $udf8        = $p['udf8']         ?? '';
        $udf9        = $p['udf9']         ?? '';
        $udf10       = $p['udf10']        ?? '';
        $additional  = $p['additionalCharges'] ?? null;

        $retHashSeq = $salt . '|' . $status . '|' .
                    $udf10 . '|' . $udf9 . '|' . $udf8 . '|' . $udf7 . '|' . $udf6 . '|' .
                    $udf5 . '|' . $udf4 . '|' . $udf3 . '|' . $udf2 . '|' . $udf1 . '|' .
                    $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;

        if (!empty($additional)) {
            $retHashSeq = $additional . '|' . $retHashSeq;
        }

        // $calc_hash = strtolower(hash('sha512', $retHashSeq));
        // $success   = hash_equals($calc_hash, strtolower($posted_hash));
        // $message   = $success
        //     ? "Payment Successful! Thank you for your donation of ₹{$amount}."
        //     : "Invalid Transaction. Please try again.";

        // if (!empty($udf1)) {
        //     CarbonDonation::where('id', $udf1)->update([
        //         'status'          => $success ? 'success' : 'failed',
        //         'gateway_txn_id'  => $txnid,
        //         'gateway_payload' => json_encode($p),
        //     ]);
        // }
        // recompute hash (your existing code)
        $calc_hash = strtolower(hash('sha512', $retHashSeq));
        $is_hash_valid = hash_equals($calc_hash, strtolower($posted_hash));
        $provider_status = strtolower(trim($status));
        $is_provider_success = ($provider_status === 'success' || $provider_status === 'successfull' || $provider_status === 'successs');
        $payment_success = $is_hash_valid && $is_provider_success;

        if ($is_hash_valid && $is_provider_success) {
            $message = "Payment Successful! Thank you for your donation of ₹{$amount}.";
        } elseif ($is_hash_valid && !$is_provider_success) {
            $message = "Payment not completed (status: {$status}). If money was debited it will be refunded by the bank. Please contact support.";
            \Log::warning('PayU: hash ok but provider status not success', [
                'txnid' => $txnid,
                'provider_status' => $status,
                'udf1' => $udf1
            ]);
        } else {
            $message = "Invalid Transaction. Please try again.";
        }

        if (!empty($udf1)) {
            CarbonDonation::where('id', $udf1)->update([
                'status'          => $payment_success ? 'success' : ($provider_status === 'pending' ? 'pending' : 'failed'),
                'gateway_txn_id'  => $txnid,
                'gateway_payload' => json_encode($p),
                'gateway_status'  => $status,
                'gateway_hash_ok' => $is_hash_valid ? 1 : 0
            ]);
        }
        $success = $payment_success;

        return view('admin.carbon.response', compact('status','message','success','txnid','amount'));
    }

    public function iframe(Request $request)
    {
        // 1) expire check
        $expires = (int) $request->query('expires', 0);
        if ($expires <= time()) {
            abort(403, 'Link expired.');
        }

        // 2) SAME ORDER/SAME NAMES as you used while generating URL
        $fields = [
            'amount'  => $request->query('amount', ''),
            'co2'     => $request->query('co2', ''),
            'trees'   => $request->query('trees', ''),
            'name'    => $request->query('name', ''),
            'email'   => $request->query('email', ''),
            'mobile'  => $request->query('mobile', ''),
            'expires' => $expires,
        ];

        // 3) recompute signature (must MATCH generator exactly)
        $params = http_build_query($fields); // keeps stable order
        $calc   = hash_hmac('sha256', $params, env('EMBED_SECRET'));
        $sig    = (string) $request->query('signature', '');

        if (!hash_equals($calc, $sig)) {
            abort(403, 'Invalid signature.');
        }

        $amount = (float) $fields['amount'];
        if ($amount <= 0) {
            abort(422, 'Amount required.');
        }

        return view('admin.carbon.realtime', [
            'amount' => $amount,
            'co2'    => (float) $fields['co2'],
            'trees'  => (int)   $fields['trees'],
            'name'   => (string)$fields['name'],
            'email'  => (string)$fields['email'],
            'mobile' => (string)$fields['mobile'],
        ]);
    }

}
