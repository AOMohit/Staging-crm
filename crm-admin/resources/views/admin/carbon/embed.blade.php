@php
  $amount  = 750;
  $co2     = 42.5;
  $trees   = 3;
  $name    = '';
  $email   = '';
  $mobile  = '';
  $expires = now()->addHours(2)->timestamp;

  $base   = rtrim(env('EMBED_ROOT'), '/'); // ngrok + subfolder
  // ORDER matters — must match controller:
  $params = http_build_query([
      'amount'  => $amount,
      'co2'     => $co2,
      'trees'   => $trees,
      'name'    => $name,
      'email'   => $email,
      'mobile'  => $mobile,
      'expires' => $expires,
  ]);
  $sig = hash_hmac('sha256', $params, env('EMBED_SECRET'));
  $iframeUrl = $base . '/donation/iframe?' . $params . '&signature=' . $sig;
@endphp

<h3>Copy this iframe and paste on any website:</h3>
<textarea style="width:100%;height:140px;">
<iframe 
  src="{{ $iframeUrl }}" 
  style="width:100%;height:520px;border:0;border-radius:12px;overflow:hidden" 
  allow="payment *; clipboard-write *">
</iframe>
</textarea>

<p>Preview:</p>
<iframe 
  src="{{ $iframeUrl }}" 
  style="width:100%;height:520px;border:0;border-radius:12px;overflow:hidden" 
  allow="payment *; clipboard-write *">
</iframe>
