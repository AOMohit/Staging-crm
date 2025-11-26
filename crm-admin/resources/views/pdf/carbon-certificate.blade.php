<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <style>
        @page {
            margin: 0;
            padding: 0;
            page-break-after: avoid;
            page-break-before: avoid;
        }

        .footers {
            position: absolute;
            width: 100%;
            left: 24%;
            top: 78%;
        }
    </style>
</head>

<body>
    <div class="container-fluid " style="width: 99.5%">
        <div style="position: relative;">
            <img src="{{ url('public/pdf/asset/images/pdf-bg.png') }}" width="100%" alt="">
        </div>
        <div style="position:absolute;top:6%;right:7%">
            <table>
                <tr>
                    <td style="text-align: center;">
                        <img src="{{ url('public/pdf/asset/images/tree.png') }}" width="239px" height="100px"
                            style="margin-right: 40%" alt="">
                    </td>
                    <td style="text-align: center;">
                        <img src="{{ url('public/pdf/asset/images/logos.png') }}" width="90px" height="90px"
                            style="margin-left:100px" alt="">
                    </td>
                </tr>
            </table>

        </div>

        <div style="position:absolute; top:23%;left:25%">

            <div style="text-align: center;padding-left:40px;">
                <h1 style="font-size: 60px;color:#4E3225;text-transform:uppercase">Certification</h1>
                <h3 style="font-size: 35px;color:#4E3225;text-transform:uppercase">of eco support</h3>
                <p style="font-size: 22px;color:#4E3225;">Congratulations to</p>
                <h1 class="text-warning" style="font-size: 30px;text-transform:uppercase">{{ $customer }}</h1>
            </div>

            <p style="margin-left:25%;margin-top:10px;margin-right:7%;font-size:14px;font-family:'Poppins', sans-serif">
                {{ $tree }} trees will be planted by
                Treeties, a
                grassroots eco-movement which has already planted more
                than 1 million trees at altitudes of 15,000 ft. Your contribution will help remove CO2 and other
                pollutants from the air we breathe. These plantations are helping biodiversity to flourish by
                creating verdant habitats for migratory birds and other wildlife. Every tree planted enables an
                entire community to sustain itself while providing for a better planet. You are now a part of a
                legacy project.<br><br>

                We, at <span class="fw-bold">Adventures Overland,</span> appreciate your contribution to a more
                environmentally friendly
                world.
            </p>
        </div>
        <div class="footers">
            <div class="footer" style=" display: flex;justify-content: space-between;">
                <table>
                    <tr>
                        <td style="text-align: center">
                            <img src="{{ url('public/pdf/asset/images/sign_2.png') }}" alt="">
                            <h6 style="font-size:15px;">
                                <p style="font-family:'Poppins', sans-serif;font-weight:bold;">TUSHAR AGARWAL</p>
                            </h6>
                            <p
                                style="font-size:14px;font-weight:400;line-height:4px;font-family:'Poppins', sans-serif;">
                                CO-FOUNDER ADVENTURES OVERLAND</p>
                        </td>
                        <td style="text-align: center;padding-left:280px">
                            <img src="{{ url('public/pdf/asset/images/sign_1.png') }}" alt="">
                            <h6 style="font-size:15px;">
                                <p style="font-family:'Poppins', sans-serif;font-weight:bold;">RAJESH PATEL</p>
                            </h6>
                            <p
                                style="font-size:14px;font-weight:400;line-height:4px;font-family:'Poppins', sans-serif;">
                                CO-FOUNDER GOLDEN MILE TRUST</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
