<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Payment Response</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f9f9f9; font-family: 'Segoe UI',sans-serif; }
    .container { max-width: 900px; }
    .card { border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
    .success { color: #0FB915; }
    .failed { color: #dc3545; }
    .btn-primary { background: #0FB915; border-color: #0FB915; }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="card p-4 text-center">
      <h2 class="{{ $success ? 'success' : 'failed' }}">
        {!! $message !!}
      </h2>

      <div class="row justify-content-center mt-3">
        <div class="col-md-8">
          <dl class="row text-start">
            <dt class="col-sm-4">Transaction ID:</dt>
            <dd class="col-sm-8">{{ $txnid }}</dd>

            <dt class="col-sm-4">Amount:</dt>
            <dd class="col-sm-8">₹{{ $amount }}</dd>

            <dt class="col-sm-4">Provider Status:</dt>
            <dd class="col-sm-8">{{ $status }}</dd>
          </dl>
        </div>
      </div>

      <div class="mt-4">
        <!-- BACK button which breaks out of iframe if needed -->
        <button id="backBtn" class="btn btn-primary btn-lg">Back to Calculator</button>
      </div>

      <small class="d-block mt-3 text-muted">If you were charged but status shows failed, please contact support with the transaction id above.</small>
    </div>
  </div>

  <script>
    // Make Back always go to the calculator route
    var targetUrl = "{{ route('carbon.calculator') }}";

    document.getElementById('backBtn').addEventListener('click', function () {
      try {
        if (window.top !== window.self) {
          window.top.location.href = targetUrl;
        } else {
          window.location.href = targetUrl;
        }
      } catch (e) {
        window.location.href = targetUrl;
      }
    });
  </script>
</body>
</html>
