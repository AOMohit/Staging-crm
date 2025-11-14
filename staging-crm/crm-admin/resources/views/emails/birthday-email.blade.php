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
            /* Link colour */
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

        body {
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #000000;
        }
    </style>
    <!-- End stylesheet -->
</head>

<body align="center" style="text-align: center; margin: 0; padding: 0; background-color: #ffffff;">

<!-- Fallback force center content -->
<div style="text-align: center;">

    <!-- Start container for logo -->
    <table align="center"
           style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff;"
           width="600">
        <tbody>
        <tr>
            <td style="padding: 15px;" width="556">
                <img src="https://adventuresoverland.com/crm-admin/storage/app/admin/setting/AO-logo-mailer.png"
                     style="width:auto;height:56px" alt="Adventures Overland Logo">
            </td>
        </tr>
        </tbody>
    </table>
    <!-- End container for logo -->

    <!-- Start single column section -->
    <table align="center"
           style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff; border-radius: 10px;"
           width="600">
        <tbody>
        <!-- Banner Image -->
        <tr>
            <td style="padding: 0;">
                <img src="https://adventuresoverland.com/crm-admin/storage/app/admin/setting/ao_birthday_bnr_mail.jpg"
                     style="width: 100%; max-width: 600px; display: block;" alt="Birthday Banner">
            </td>
        </tr>
        <!-- Content -->
        <tr>
            <td style="font-size: 15px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; color: #383838; padding: 25px;text-align: left;">
                <p>Dear {{ $data['name'] }},</p>
                <p>Happy Birthday! 🎉</p>
                <p>On your special day, we wanted to honor you in a truly meaningful way. 🌟</p>
                <p>In collaboration with our NGO partner <a href="https://treeties.co.in/ourexperience">Treeties</a>,
                    <b>Adventures Overland</b> has planted a tree in your name.
                    A certificate of authentication is attached to this email. This gesture is a small step towards
                    contributing to a greener planet. 🌱🌍</p>
                <p>Thank you for being a valued part of our Road Trips. We hope this small gesture brings a smile
                    to your face as you celebrate your special day.</p>
                <p>Wishing you joy, health, and prosperity in the year ahead!</p>
                <p>Warm regards,<br>
                    Team Adventures Overland</p>
            </td>
        </tr>
        </tbody>
    </table>
    <!-- End single column section -->

    <!-- Start footer section -->
    <table align="center" style="text-align: center; vertical-align: top; width: 600px; max-width: 600px;" width="600">
        <tbody>
        <tr>
            <td style="padding: 30px;" width="596">
                <img src="https://adventuresoverland.com/crm-admin/storage/app/admin/setting/AO-footer-mailer.png"
                     style="width:auto;height:160px;" alt="Footer Logo">
                <p style="font-size: 12px; line-height: 15px; font-family: 'Helvetica', Arial, sans-serif; color: #000000; margin-top: 5px;">
                    This email was sent to {{ $data['email'] }}.<br>
                    To help secure your account, please don't forward this email.
                </p>
            </td>
        </tr>
        </tbody>
    </table>
    <!-- End footer section -->

</div>

</body>

</html>
