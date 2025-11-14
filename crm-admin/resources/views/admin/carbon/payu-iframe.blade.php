<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Redirecting to PayU...</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body { font-family: Arial, Helvetica, sans-serif; text-align:center; padding:40px; }
    .btn { padding:10px 16px; background:#0FB915; color:#fff; border-radius:6px; border:none; cursor:pointer; }
  </style>
</head>
<body>
  <p>Please wait, redirecting to PayU...</p>

  <form id="payuForm" name="payuForm" action="{{ rtrim($base_url, '/') }}/_payment" method="post" target="_top" autocomplete="off">
      <input type="hidden" name="key"         value="{{ $key }}">
      <input type="hidden" name="txnid"       value="{{ $txnid }}">
      <input type="hidden" name="amount"      value="{{ $amount }}">
      <input type="hidden" name="productinfo" value="{{ $productinfo }}">
      <input type="hidden" name="firstname"   value="{{ $firstname }}">
      <input type="hidden" name="email"       value="{{ $email }}">
      <input type="hidden" name="phone"       value="{{ $phone }}">
      <input type="hidden" name="surl"        value="{{ $surl }}">
      <input type="hidden" name="furl"        value="{{ $furl }}">
      <input type="hidden" name="hash"        value="{{ $hash }}">
      <input type="hidden" name="service_provider" value="payu_paisa">

      {{-- UDFs --}}
      <input type="hidden" name="udf1"  value="{{ $udf1 }}">
      <input type="hidden" name="udf2"  value="{{ $udf2 }}">
      <input type="hidden" name="udf3"  value="{{ $udf3 }}">
      <input type="hidden" name="udf4"  value="{{ $udf4 }}">
      <input type="hidden" name="udf5"  value="{{ $udf5 }}">
      <input type="hidden" name="udf6"  value="{{ $udf6 }}">
      <input type="hidden" name="udf7"  value="{{ $udf7 }}">
      <input type="hidden" name="udf8"  value="{{ $udf8 }}">
      <input type="hidden" name="udf9"  value="{{ $udf9 }}">
      <input type="hidden" name="udf10" value="{{ $udf10 }}">

      <noscript>
        <p>JavaScript is disabled: click proceed to continue to PayU.</p>
        <button type="submit" class="btn">Proceed</button>
      </noscript>
  </form>

  <script>
    // small delay + try/catch to avoid rare race conditions
    setTimeout(function(){
      try {
        var f = document.getElementById('payuForm');
        if (f) f.submit();
      } catch (e) {
        // fallback: show the form's submit button if needed
        console.error('Auto-submit failed, user can proceed manually.', e);
      }
    }, 250);
  </script>
</body>
</html>
