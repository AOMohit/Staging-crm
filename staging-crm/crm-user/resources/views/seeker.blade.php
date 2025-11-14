<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Geologica:wght@100;300&family=Lato&family=Open+Sans&family=Poppins&family=Roboto&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <title>{{ setting('site_name') }}</title>
    <link href="{{ asset('public/userpanel') }}/asset/css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ env('ADMIN_URL') . 'storage/app/' . setting('logo') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    <style>
        /*::-webkit-scrollbar {*/
        /*    display: none;*/
        /*}*/

        /* #fieldsContainer{
            display: none;
        } */

       .symbol{
        color:red;
       }
    </style>
</head>

<body>
    <header class="header-bg ">
    </header>

    <nav class="navbar sticky-top navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('public/userpanel') }}/asset/images/logo.png" alt="">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                aria-controls="offcanvasRight" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">

                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>

            </div>
            <div class="text-end d-none d-md-block">
                <a href="#" class="btn-btn-header">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19"
                            fill="none">
                            <path
                                d="M9 0C8.01109 0 7.04439 0.293245 6.22215 0.842652C5.3999 1.39206 4.75904 2.17295 4.3806 3.08658C4.00216 4.00021 3.90315 5.00555 4.09607 5.97545C4.289 6.94536 4.7652 7.83627 5.46447 8.53553C6.16373 9.2348 7.05464 9.711 8.02455 9.90393C8.99445 10.0969 9.99979 9.99784 10.9134 9.6194C11.827 9.24096 12.6079 8.6001 13.1573 7.77785C13.7068 6.95561 14 5.98891 14 5C14 3.67392 13.4732 2.40215 12.5355 1.46447C11.5979 0.526784 10.3261 0 9 0ZM9 8C8.40666 8 7.82664 7.82405 7.33329 7.49441C6.83994 7.16476 6.45542 6.69623 6.22836 6.14805C6.0013 5.59987 5.94189 4.99667 6.05764 4.41473C6.1734 3.83279 6.45912 3.29824 6.87868 2.87868C7.29824 2.45912 7.83279 2.1734 8.41473 2.05764C8.99667 1.94189 9.59987 2.0013 10.1481 2.22836C10.6962 2.45542 11.1648 2.83994 11.4944 3.33329C11.8241 3.82664 12 4.40666 12 5C12 5.79565 11.6839 6.55871 11.1213 7.12132C10.5587 7.68393 9.79565 8 9 8ZM18 19V18C18 16.1435 17.2625 14.363 15.9497 13.0503C14.637 11.7375 12.8565 11 11 11H7C5.14348 11 3.36301 11.7375 2.05025 13.0503C0.737498 14.363 0 16.1435 0 18V19H2V18C2 16.6739 2.52678 15.4021 3.46447 14.4645C4.40215 13.5268 5.67392 13 7 13H11C12.3261 13 13.5979 13.5268 14.5355 14.4645C15.4732 15.4021 16 16.6739 16 18V19H18Z"
                                fill="black" />
                        </svg> &nbsp; WELCOME <span class="text-uppercase">
                           @if($data)
                                {{ $data->first_name }}
                                {{ $data->last_name }}
                            @endif
                        </span>
                    </span>
                </a>
            </div>
        </div>
    </nav>
   
    <div class="wrap mt-5 mb-5">
        <div class="container">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-8 col-12 mx-auto d-block">
                        @if($data && $data->profile)
                        <img src="{{ asset('storage/app/' . $data->profile) }}" alt="" width="100px">
                        @else
                            <img src="{{ asset('default-user.png') }}" alt="Default User Image" class="img-fluid rounded-circle" 
                                width="150" height="150">
                        @endif
                        <h1 class="text-center">SEEKER FORM</h1>

                        <form action="{{ route('seekerFormSubmission') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="email" value="{{ $data->email }}">
                            <div class="row">
                            <div class="col-md-12 col-12 my-2">
                                <label for="">Tell us something about yourself in 20 words</label>
                                <textarea name="something" id="" cols="10" rows="3" class="form-control  @error('something') is-invalid @enderror">@if($data){{ $data->something }}@endif</textarea>
                                @error('something')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                <label for="">Have you done a road trip before? If yes, where? <span class="symbol">*</span></label>
                                <input type="text" name="have_road_trip"
                                    value="@if($data){{ $data->have_road_trip }}@endif" placeholder="" class="form-control @error('have_road_trip') is-invalid @enderror"
                                    id="">
                                @error('have_road_trip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                <label for="">Most Thrilling Experience of your life? <span class="symbol">*</span></label>
                                <input type="text" name="thrilling_exp" 
                                    value="@if($data){{ $data->thrilling_exp }}@endif" placeholder="" class="form-control @error('thrilling_exp') is-invalid @enderror"
                                    id="">
                                    @error('thrilling_exp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                <label for="">Three Travel essentials for Road Trips?<span class="symbol">*</span></label>
                                <input type="text" name="three_travel" 
                                    value="@if($data){{ $data->three_travel }}@endif" placeholder="" class="form-control @error('three_travel') is-invalid @enderror"
                                    id="">
                                    @error('three_travel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                <label for="">Three places in your bucket list? <span class="symbol">*</span></label>
                                <input type="text" name="three_place" 
                                    value="@if($data){{ $data->three_place }}@endif" placeholder="" class="form-control @error('three_place') is-invalid @enderror"
                                    id="">
                                    @error('three_place')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                </div>
                            <button class="button rounded-pill p-2 px-5 mt-2">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            window.location.href = "{{ url('/') }}";
        });
    </script>
    @endif
</body>

</html>
