<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <style type="text/css">
        a, a[href], a:hover, a:link, a:visited { text-decoration: none !important; color: #0000EE; }
        .link { text-decoration: underline !important; }
        h1 { font-size: 22px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: normal; color: #000000; }
        .summary-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .summary-table td { padding: 8px 6px; border: 1px solid #e6e6e6; font-family: 'Helvetica', Arial, sans-serif; font-size: 14px; color: #333333; }
        .summary-table .label { background:#f7f7f7; width: 35%; font-weight: 600; }
    </style>
</head>
<body style="text-align: center; margin: 0; padding:10px 0; -webkit-text-size-adjust: 100%; background-color: #ffffff; color: #000000" align="center">
    <div style="text-align: center;">
        <!-- Logo -->
        <table align="center" width="600" style="max-width:600px;">
            <tr>
                <td style="padding:15px 0;">
                    <img src="{{ asset('storage/app/admin/setting/AO-logo-mailer.png') }}" alt="Logo" style="width:auto;height:56px">
                </td>
            </tr>
        </table>
        <!-- Content -->
        <table align="center" width="600" style="max-width:600px; border-radius:10px; background:#fff;">
            <tr>
                <td style="font-size:15px; line-height:24px; font-family:Helvetica, Arial, sans-serif; color:#383838; padding:30px; text-align:left;">
                    <p style="margin:0 0 12px 0;">Hello <strong>Team</strong>,</p>
                    <p style="margin:0 0 12px 0;">
                        Please find the expense details below for
                        <strong>{{ $data['trip_name'] ?? 'Deleted' }}</strong>.
                    </p>
                    <table class="summary-table" cellpadding="0" cellspacing="0" role="presentation">
                        <tr><td class="label">Action</td><td>{{ $data['action'] ?? '-' }}</td></tr>
                        <tr><td class="label">Trip</td><td>{{ $data['trip_name'] ?? 'Deleted' }}</td></tr>
                        <tr><td class="label">Expense ID</td><td>{{ $data['expense_id'] ?? '-' }}</td></tr>
                        <tr><td class="label">Vendor</td><td>{{ $data['vendor_name'] ?? 'N/A' }}</td></tr>
                        <tr><td class="label">Service</td><td>{{ $data['vendor_service_name'] ?? 'N/A' }}</td></tr>
                        <tr><td class="label">Total Amount</td><td>{{ $data['total_amount'] ?? '-' }}</td></tr>
                        <tr><td class="label">Comment</td><td>{!! nl2br(e($data['comment'] ?? '-')) !!}</td></tr>
                        <tr><td class="label">Date</td><td>{{ $data['today'] ?? date('Y-m-d') }}</td></tr>
                    </table>
                    @if(!empty($data['attachment']))
                        <p style="margin-top:14px;margin-bottom:0;"><strong>Attachment:</strong> The report/document is attached with this email.</p>
                    @endif
                    <p style="margin-top:16px;">Kindly review and take necessary action.</p>
                </td>
            </tr>
        </table>
        <!-- Footer -->
        <table align="center" width="600" style="max-width:600px;">
            <tr>
                <td style="padding:30px;text-align:center;">
                    <img src="{{ asset('storage/app/admin/setting/AO-footer-mailer.png') }}" alt="Footer" style="width:auto;height:160px">
                    <p style="font-size:12px;color:#000;margin-top:8px;">This is an automated email. Please do not reply.</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
