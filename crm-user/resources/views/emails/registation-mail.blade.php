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
                        <img src="{{ env('ADMIN_URL') . 'storage/app/admin/setting/AO-logo-mailer.png'}}" style="width:auto;height:56px">

                    </td>
                </tr>
            </tbody>
        </table>
        <!-- End container for logo -->

        <!-- Start single column section -->
        <table align="center"
            style="text-align: left; border-radius: 10px; vertical-align: top; width: 600px; min-width: 600px;"
            width="600">
            <tbody>
                <tr>
                    <td style="font-size: 15px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 400; text-decoration: none; color: #383838; width: 596px; vertical-align: top; padding-top: 30px; padding-bottom: 40px;"
                        width="596">

                        <p>Hello Admin,<br>Please find the below user data:</p>
                        {{-- <p>
                    Thank you for your enquiry. Someone from our team will get back to you very soon.
                </p>
                 --}}
                    </td>
                </tr>
                <table align="center" style="text-align: left; vertical-align: top; width: 600px; min-width: 600px;background-color:white"
            width="600">
            <tbody>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>First Name:</th>
                    <td>{{ $mailData['first_name'] }}</td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Last Name:</th>
                    <td>{{ $mailData['last_name'] }}</td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Email:</th>
                    <td>{{ $mailData['email'] }}</td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Phone:</th>
                    <td>{{ $mailData['phone'] }}</td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Address:</th>
                    <td>{{ $mailData['address'] }}</td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>City:</th>
                    <td>{{ $mailData['city'] }}</td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>State:</th>
                    <td>{{ $mailData['state'] }}</td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Country:</th>
                    <td>{{ $mailData['country'] }}</td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Pincode:</th>
                    <td>{{ $mailData['pincode'] }}</td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Date Of Birth:</th>
                    <td>{{ $mailData['dob'] }}</td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Meal Preference:</th>
                    <td>{{ $mailData['meal_preference'] }}</td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Blood Group:</th>
                    <td>{{ $mailData['blood_group'] }}</td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Profession:</th>
                    <td>{{ $mailData['profession'] }}</td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Emergency Contact:</th>
                    <td>{{ $mailData['emg_contact'] }}</td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Emergency name:</th>
                    <td>{{ $mailData['emg_name'] }}</td>
                </tr>

                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>T-Shirt Size:</th>
                    <td>{{ $mailData['t_size'] }}</td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Profile Image</th>
                    <td>
                    <div class="button-div" style=" margin-top: 10px; margin-bottom: 10px;">
                    <a href="{{ $mailData['profile'] }}" class="button p-2" style=" background-color: #FFB224; text-decoration: none; color: white; padding: 5px; 12px; border-radius: 5px;">Download</a>
                    </div> 
                    </td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Passport Front</th>
                    <td>
                        <div class="button-div" style=" margin-top: 10px; margin-bottom: 10px;">
                    <a href="{{ $mailData['passport_front'] }}" class="button p-2" style=" background-color: #FFB224; text-decoration: none; color: white; padding: 5px; 12px; border-radius: 5px;">Download</a>
                    </div> 
                    </td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Passport Back</th>
                    <td>
                        <div class="button-div" style=" margin-top: 10px; margin-bottom: 10px;">
                    <a href="{{ $mailData['passport_back'] }}" class="button p-2" style=" background-color: #FFB224; text-decoration: none; color: white; padding: 5px; 12px; border-radius: 5px;">Download</a>
                    </div> 
                    </td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Pan Card/GST Certificate</th>
                    <td>
                        <div class="button-div" style=" margin-top: 10px; margin-bottom: 10px;">
                    <a href="{{ $mailData['pan_gst'] }}" class="button p-2" style=" background-color: #FFB224; text-decoration: none; color: white; padding: 5px; 12px; border-radius: 5px;">Download</a>
                    </div> 
                    </td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Driving Licence</th>
                    <td>
                        <div class="button-div" style=" margin-top: 10px; margin-bottom: 10px;">
                    <a href="{{ $mailData['driving'] }}" class="button p-2" style=" background-color: #FFB224; text-decoration: none; color: white; padding: 5px; 12px; border-radius: 5px;">Download</a>
                    </div> 
                    </td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Aadhar Card</th>
                    <td>
                        <div class="button-div" style=" margin-top: 10px; margin-bottom: 10px;">
                    <a href="{{ $mailData['adhar_card'] }}" class="button p-2" style=" background-color: #FFB224; text-decoration: none; color: white; padding: 5px; 12px; border-radius: 5px;">Download</a>
                    </div> 
                    </td>
                </tr>
                <tr style="border-bottom: 1px rgb(200, 190, 190) solid">
                    <th>Extra Document</th>
                    @foreach ($mailData['extra_doc'] as $docs)
                      
                      <td>{{$docs->title}}:- <img src="{{ url('storage/app/'.$docs->image) }}" width="100px" alt=""></td><br>
                    @endforeach
                </tr>
               
            </tbody>
        </table>
                
            </tbody>
            
        </table>
        <!-- End single column section -->
        <!-- Start unsubscribe section -->
        
        <!-- End unsubscribe section -->
        <table align="center" style="text-align: center; vertical-align: top; width: 600px; max-width: 600px;"
            width="600">
            <tbody>
                <tr>
                    <td style="width: 596px; vertical-align: top; padding-left: 30px; padding-right: 30px; padding-top: 30px; padding-bottom: 30px;"
                        width="596">
                        <img src="{{ env('ADMIN_URL') . 'storage/app/admin/setting/AO-footer-mailer.png'}}" style="width:auto;height:160px">
                        <p
                            style="font-size: 12px; line-height: 15px; font-family: 'Helvetica', Arial, sans-serif; font-weight: normal; text-decoration: none; color: #000000;">
                            This email was sent to {{ $mailData['email'] }}.<br>
                            To help secure your account, please dont forward this email.
                        </p>

                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</body>

</html>
