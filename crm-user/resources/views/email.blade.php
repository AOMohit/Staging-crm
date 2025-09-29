<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Geologica:wght@100;300&family=Lato&family=Open+Sans&family=Poppins&family=Roboto&display=swap" rel="stylesheet">
   <style>
        body{
            background-color: #FFF8EA;
            font-family: Fira Sans;

        }
        .container{
            padding-block: 40px;
        }
        .col-10{
            background-color:white; 
            width:640px ;height:530px;
            border-radius: 10px;
            padding: 30px;
            align-items: center;
            
            
        }
        .item-center{
            margin-inline: auto;
        }
        .border{
            border-bottom: #D2E8FF solid 2px;
            width:700px;
            padding-top:20px;
        }
        .h6{
            text-align: center;
            font-size: 14px;
        }
        .p{
            margin-top: 10px;
            text-align: center;
            font-size: 12px;
        }
        .button-div{
            margin-top: 50px;
        }
        .button{
            
            background-color: #FFB224;
            text-decoration: none;
            color: white;
            padding: 10px;
            border-radius: 10px;
        }
   </style>
</head>
<body style=" background-color: #FFF8EA;font-family: Fira Sans;padding-top:40px;padding-bottom:40px;">
    
    <div class="container">
        <div class="col-10 " style="background-color:white; width:640px ;height:530px;border-radius: 10px;padding: 30px;margin-left:auto;margin-right:auto;">
            <div class="row mb-3">
                <h5 class="text" style="font-size:20px;">Points Redeem Thanks Email</h5>

                <p style="font-size:14px;">Hi {{$mailData['first_name']}} {{$mailData['last_name']}},</p>
                <p style="font-size:14px;">You have succesfully redeemed {{$mailData['points']}} points for {{$mailData['trip_name']}} Trip on the following dates {{$mailData['date']}}. Your new account balance for this trip is {{$mailData['bal_points']}}.</p>

            </div>
            <div class="button-div" style=" margin-top: 50px;">

                <a href="" class="button p-2" style=" background-color: #FFB224;
            text-decoration: none;
            color: white;
            padding: 10px;
            border-radius: 10px;">Open Link</a>
            </div>
        </div>

        <div class="border item-center" style="border-bottom:#D2E8FF solid 2px;width:700px;padding-top:20px;margin-left:auto;margin-right:auto;">

        </div>
       
        <div class="item-center">
            {{-- <div class=""> --}}
                 <h6 class="h6" style="text-align: center;
            font-size: 14px;">
                    © 2024 Adventures Overland Pvt Ltd., All Rights Reserved.<br>
                1023, Tower B4, 10th Floor, Spaze IT Tech Park, Sohna Road, Gurugram, Haryana-122018 
                </h6>
                <p class="p" style="margin-top: 10px;
            text-align: center;
            font-size: 12px;">
                    This email was sent to {{$mailData['email']}}. If you dont want to recieve these emails fom Adventures Overland in the future, please <br> unsubscribe from Adventures Overland Platform. <br>To help secure your account, please dont forward this email.</p>
            {{-- </div> --}}
        </div>
    </div>
</body>
</html>