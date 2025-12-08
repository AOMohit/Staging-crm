<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <!-- Start stylesheet -->
    <style type="text/css">
      a,a[href],a:hover, a:link, a:visited { text-decoration: none!important; color: #0000EE; }
      .link { text-decoration: underline!important; }
      h1 {
        font-size:22px;
        line-height:24px;
        font-family:'Helvetica', Arial, sans-serif;
        font-weight:normal;
        text-decoration:none;
        color: #000000;
      }
      .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td {line-height: 100%;}
      .ExternalClass {width: 100%;}
      .email-content-text { text-align: justify !important; }
    </style>
</head>
  <body style="text-align: center; margin: 0; padding-top: 10px; padding-bottom: 10px; padding-left: 0; padding-right: 0; -webkit-text-size-adjust: 100%;background-color: #ffffff; color: #000000" align="center">
    <div style="text-align: center;">
        <table align="center" style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff;" width="600">
            <tbody>
                <tr>
                <td style="width: 596px; vertical-align: top; padding-left: 0; padding-right: 0; padding-top: 15px; padding-bottom: 15px;" width="596">
                    <img src="{{ asset('storage/app/admin/setting/AO-logo-mailer.png') }}" style="width:auto;height:56px">
                </td>
                </tr>
            </tbody>
        </table>

        <table class="email-content-text" width="600" style="max-width:600px; margin:0 auto;">
            <tr>
                <td style="padding:16px; background:#fff;">
                    <p style="margin:0 0 8px 0;">Hello OPS Team,</p>

                    <p style="margin:6px 0;"><strong>New trip booking received</strong> from <strong>{{ $data['name'] ?? 'N/A' }}</strong> for <strong>{{ $data['trip_name'] ?? 'N/A' }}</strong></p>

                    <ul style="font-size:14px; padding-left:16px; margin-top:8px;">
                        <li><strong>Email:</strong> {{ $data['email'] ?? 'N/A' }}</li>
                        <li><strong>Phone:</strong> {{ $data['phone'] ?? 'N/A' }}</li>
                        <li><strong>Spoc Person:</strong> {{ $data['spoc_person'] ?? 'N/A' }}</li>
                        <li><strong>Received Amount:</strong> {{ $data['paid_amt'] ?? 'N/A' }}</li>
                        <li><strong>Slot:</strong> {{ $data['slot'] ?? 'N/A' }}</li>
                    </ul>

                    <h4 style="margin-top:12px; margin-bottom:6px;">A. Persona of guests:</h4>
                    <p style="margin:4px 0 10px 0;">
                        {{ $data['persona_of_guests'] ?? '-' }}
                    </p>

                    <h4 style="margin-top:12px; margin-bottom:6px;">B. Billing Details:</h4>

                    {{-- =====================  B. BILLING TABLE  ====================== --}}
                    <table width="100%" cellpadding="4" cellspacing="0" style="border-collapse:collapse; font-size:13px; border:1px solid #000;">
                        {{-- header row --}}
                        <tr>
                            <td colspan="3" style="border:1px solid #000; background:#fff6a0;">&nbsp;</td>
                            <td style="border:1px solid #000; background:#ffe3d4; text-align:center; font-weight:bold;">INR</td>
                            <!-- <td style="border:1px solid #000; background:#d8f5dd;">&nbsp;</td> -->
                        </tr>

                        {{-- Participation cost per person --}}
                        <tr>
                            <td style="border:1px solid #000; font-style:italic;">
                                Participation Cost per person
                            </td>
                            <td style="border:1px solid #000;">
                                INR {{ $data['per_person_cost'] ?? '' }}
                            </td>
                            <td style="border:1px solid #000;">
                                {{ $data['travellers_text'] ?? '' }} {{-- e.g. "x2 travelers" --}}
                            </td>
                            <td style="border:1px solid #000; background:#ffe3d4;">
                                {{ $data['per_person_total'] ?? '' }}
                            </td>
                            <!-- <td style="border:1px solid #000; background:#d8f5dd;">&nbsp;</td> -->
                        </tr>

                        {{-- Exclusive vehicle --}}
                        <tr>
                            <td style="border:1px solid #000; font-style:italic;">
                                Exclusive Vehicle
                            </td>
                            <td style="border:1px solid #000;">
                                INR {{ $data['exclusive_vehicle_cost'] ?? '' }}
                            </td>
                            <td style="border:1px solid #000;">
                                {{ $data['exclusive_vehicle_text'] ?? '' }} {{-- e.g. "x1 exclusive vehicle" --}}
                            </td>
                            <td style="border:1px solid #000; background:#ffe3d4;">
                                {{ $data['exclusive_vehicle_total'] ?? '' }}
                            </td>
                            <!-- <td style="border:1px solid #000; background:#d8f5dd;">&nbsp;</td> -->
                        </tr>

                        {{-- Room category --}}
                        @if(!empty($data['room_info']) && is_array($data['room_info']))
                            @foreach($data['room_info'] as $room)
                                <tr>
                                    <td style="border:1px solid #000; font-style:italic;">
                                        Room Category (dropdown value)
                                    </td>
                                    <td style="border:1px solid #000;">
                                        INR {{ $room['room_type_amt'] ?? '' }}
                                    </td>
                                    <td style="border:1px solid #000;">
                                        {{ $room['room_type'] ?? '' }} x {{ $room['room_cat'] ?? '' }}
                                    </td>
                                    <td style="border:1px solid #000; background:#ffe3d4;">
                                        {{ $room['room_type_amt'] ?? '' }}
                                    </td>
                                    <!-- <td style="border:1px solid #000; background:#d8f5dd;">&nbsp;</td> -->
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td style="border:1px solid #000; font-style:italic;">
                                    Room Category (dropdown value)
                                </td>
                                <td style="border:1px solid #000;">Amount</td>
                                <td style="border:1px solid #000;"></td>
                                <td style="border:1px solid #000; background:#ffe3d4;"></td>
                                <!-- <td style="border:1px solid #000; background:#d8f5dd;"></td> -->
                            </tr>
                        @endif

                        {{-- Vehicle security --}}
                        <tr>
                            <td style="border:1px solid #000; font-style:italic;">
                                Vehicle Security
                            </td>
                            <td style="border:1px solid #000;">
                                {{ $data['vehicle_security_label'] ?? 'Amount' }}
                            </td>
                            <td style="border:1px solid #000;">{{ $data['vehicle_security_text'] ?? '' }}</td>
                            <td style="border:1px solid #000; background:#ffe3d4;">
                                {{ $data['vehicle_security_total'] ?? '' }}
                            </td>
                            <!-- <td style="border:1px solid #000; background:#d8f5dd;">&nbsp;</td> -->
                        </tr>

                        {{-- Total (A) --}}
                        <tr>
                            <td style="border:1px solid #000; font-style:italic;">
                                Total (A)
                            </td>
                            <td style="border:1px solid #000;">&nbsp;</td>
                            <td style="border:1px solid #000;">&nbsp;</td>
                            <td style="border:1px solid #000; background:#ffe3d4;">
                                {{ $data['total'] ?? '0' }}
                            </td>
                            <!-- <td style="border:1px solid #000; background:#d8f5dd;">&nbsp;</td> -->
                        </tr>

                        {{-- Taxes (B) --}}
                        <tr>
                            <td style="border:1px solid #000; font-style:italic;">
                                Taxes (B)
                            </td>
                            <td style="border:1px solid #000;">&nbsp;</td>
                            <td style="border:1px solid #000;">&nbsp;</td>
                            <td style="border:1px solid #000; background:#ffe3d4;">
                                {{ $data['tax'] ?? '0' }}
                            </td>
                            <!-- <td style="border:1px solid #000; background:#d8f5dd;">&nbsp;</td> -->
                        </tr>

                        {{-- Grand Total (A+B) --}}
                        <tr>
                            <td style="border:1px solid #000; font-style:italic; background:#fff6a0;">
                                Grand Total (A+B)
                            </td>
                            <td style="border:1px solid #000; background:#fff6a0;">&nbsp;</td>
                            <td style="border:1px solid #000; background:#fff6a0;">&nbsp;</td>
                            <td style="border:1px solid #000; background:#ffe3d4;">
                                {{ $data['grand_total'] ?? '0' }}
                            </td>
                            <!-- <td style="border:1px solid #000; background:#d8f5dd;">&nbsp;</td> -->
                        </tr>
                    </table>
                    {{-- ===================  END B. BILLING TABLE  ===================== --}}

                    <h4 style="margin:6px 0;">Booking Remarks:</h4>
                    <p style="margin-top:4px;">{{ $data['comment'] ?? '' }}</p>

                    <p style="margin:6px 0;">C. No. of Room(s): {{ $data['rooms'] ?? 'N/A' }}</p>
                    <p style="margin:6px 0;">D. No. of seat(s) / exclusive vehicle: {{ $data['vehicle_info'] ?? 'N/A' }}</p>

                    <h4 style="margin:6px 0;">E. Payment Schedule:</h4>
                    @if(!empty($data['payment_schedule']) && is_array($data['payment_schedule']))
                        <table width="100%" cellpadding="4" cellspacing="0" style="border-collapse:collapse; font-size:13px; border:1px solid #000; margin-top:4px;">
                            <tr style="background:#f2f2f2;">
                                <th style="border:1px solid #000; text-align:left;">S. No.</th>
                                <th style="border:1px solid #000; text-align:left;">Amount (INR)</th>
                                <th style="border:1px solid #000; text-align:left;">Due Date</th>
                                <th style="border:1px solid #000; text-align:left;">Comment</th>
                            </tr>
                            @foreach($data['payment_schedule'] as $index => $ps)
                                <tr>
                                    <td style="border:1px solid #000;">{{ $index + 1 }}</td>
                                    <td style="border:1px solid #000;">{{ $ps['amount'] ?? '' }}</td>
                                    <td style="border:1px solid #000;">
                                        {{ !empty($ps['date']) ? \Carbon\Carbon::parse($ps['date'])->format('d/m/Y') : '' }}
                                    </td>
                                    <td style="border:1px solid #000;">{{ $ps['comment'] ?? '' }}</td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <p>-</p>
                    @endif


                    <p style="margin-top:18px;">--<br/>Thanks,<br/><em>{{ setting('site_name') ?? config('app.name') }}</em></p>
                </td>
            </tr>

            <tr>
                <td style="text-align:center; padding:12px;">
                    <img src="{{ asset('storage/app/admin/setting/AO-footer-mailer.png') }}" style="height:120px;" alt="footer">
                    <p style="font-size:12px; color:#666; margin:6px 0 0;">This email was sent to {{ $data['admin_email'] ?? '' }}. To help secure your account, please don't forward this email.</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>