<!DOCTYPE HTML
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">


    <!-- Start stylesheet -->
    <style type="text/css">
        a,
        a[href],
        a:hover,
        a:link,
        a:visited {
            /* This is the link colour */
            text-decoration: none !important;
            color: #0000EE;
        }

        .link {
            text-decoration: underline !important;
        }

        h1 {
            /* Fallback heading style */
            font-size: 22px;
            line-height: 24px;
            font-family: 'Helvetica', Arial, sans-serif;
            font-weight: normal;
            text-decoration: none;
            color: #000000;
        }

        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td {
            line-height: 100%;
        }

        .ExternalClass {
            width: 100%;
        }
    </style>
    <!-- End stylesheet -->

</head>

<!-- You can change background colour here -->

<body
    style="text-align: center; margin: 0; padding-top: 10px; padding-bottom: 10px; padding-left: 0; padding-right: 0; -webkit-text-size-adjust: 100%;background-color: #ffffff; color: #000000"
    align="center">

    <!-- Fallback force center content -->
    <div style="text-align: center;">


        <!-- Start container for logo -->
        <table align="center"
            style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff;"
            width="600">
            <tbody>
                <tr>
                    <td style="width: 596px; vertical-align: top; padding-left: 0; padding-right: 0; padding-top: 15px; padding-bottom: 15px;"
                        width="596">

                        <!-- Your logo is here -->
                        <img src="{{ asset('storage/app/admin/setting/AO-logo-mailer.png') }}" style="width:auto;height:56px">

                    </td>
                </tr>
            </tbody>
        </table>
        <!-- End container for logo -->

        <!-- Start single column section -->
        <table align="center"
            style="text-align: left; border-radius: 10px; vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff;"
            width="600">
            <tbody>
                <tr>
                    <td style="font-size: 15px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 400; text-decoration: none; color: #383838; width: 596px; vertical-align: top; padding-left: 30px; padding-right: 30px; padding-top: 30px; padding-bottom: 40px;"
                        width="596">

                        <p>Hello {{ $data['name'] }},</p>
                        <p>Greetings for the day!</p>
                        <p>We hope you're excited about your upcoming trip with us! This email serves as a friendly
                            reminder of your pending payments. Kindly check the below details: </p>
                        <p>
                            <strong>₹{{ $data['pending_amt'] }}</strong> {{ $data['due_type'] }} due on
                            <strong>{{ $data['due_date'] }}</strong>.
                        </p>
                        <p>
                            Your total trip cost is <strong>₹{{ $data['total_rec'] }}</strong>.
                        </p>
                        <p><strong>Bank Account Details:</strong></p>
                            <p>Account Name    : Adventures Overland Private Limited</p>
                            <p>Bank Name       : HDFC Bank</p>
                            <p>Account Number  : 50200038391372</p>
                            <p>IFSC Code       : HDFC0001098</p>
                            <p>SWIFT Code      : HDFCINBB</p>

                            <p>In case of any queries, please feel free to contact us at <a href="mailto:info@adventuresoverland.com">info@adventuresoverland.com</a></p>
                            <p>Regards,<br>
Team Adventures Overland</p>

                    </td>
                </tr>
            </tbody>
        </table>
        <!-- End single column section -->
        <!-- Start unsubscribe section -->
                <table align="center" style="text-align: center; vertical-align: top; width: 600px; max-width: 600px;"
            width="600">
            <tbody>
                <tr>
                    <td style="width: 596px; vertical-align: top; padding-left: 30px; padding-right: 30px; padding-top: 30px; padding-bottom: 30px;"
                        width="596">
<img src="{{ asset('storage/app/admin/setting/AO-footer-mailer.png') }}" style="width:auto;height:160px">
                        <p
                            style="font-size: 12px; line-height: 15px; font-family: 'Helvetica', Arial, sans-serif; font-weight: normal; text-decoration: none; color: #000000; margin-top: 5px;">
                            This email was sent to {{ $data['email'] }}.<br>
                            To help secure your account, please dont forward this email.
                        </p>

                    </td>
                </tr>
            </tbody>
        </table>
        <!-- End unsubscribe section -->

    </div>

</body>

</html>
