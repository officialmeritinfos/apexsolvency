<!doctype html>
<html lang="zxx">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap Min CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/user/css/bootstrap.min.css')}}">
    <!-- Owl Theme Default Min CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/user/css/owl.theme.default.min.css')}}">
    <!-- Owl Carousel Min CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/user/css/owl.carousel.min.css')}}">
    <!-- Animate Min CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/user/css/animate.min.css')}}">
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <!-- Remixicon CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/user/css/remixicon.css')}}">
    <!-- boxicons CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/user/css/boxicons.min.css')}}">
    <!-- MetisMenu Min CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/user/css/metismenu.min.css')}}">
    <!-- Simplebar Min CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/user/css/simplebar.min.css')}}">
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/user/css/style.css')}}">
    <!-- Dark Mode CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/user/css/dark-mode.css')}}">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/user/css/responsive.css')}}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{asset('home/images/'.$web->logo)}}">
    <title>{{$pageName}} - {{$siteName}}</title>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</head>

<body class="body-bg-f5f5f5">
<!-- Start Preloader Area -->
<div class="preloader">
    <div class="content">
        <div class="box"></div>
    </div>
</div>
<!-- End Preloader Area -->

<!-- Start User Area -->
<section class="user-area">
    <div class="container">
        <div class="user-form-content">
            <h3>Register</h3>
            <p>Register to continue to {{$siteName}}.</p>

            <form class="user-form" method="post" action="{{route('auth.register')}}">
                @include('templates.notification')
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" type="text" placeholder="Enter your name"
                                   value="{{old('name')}}" name="name">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Username</label>
                            <input class="form-control" type="text" placeholder="Enter your username"
                                   name="username" value="{{old('username')}}"/>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control" type="email"  name="email" value="{{old('email')}}"
                                   placeholder="Enter your email">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>Phone</label>
                            <input class="form-control" type="text" placeholder="Enter your Phone"
                                   name="phone" value="{{old('phone')}}"/>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control" type="password" name="password"
                                   placeholder="Enter your password">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>Repeat password</label>
                            <input class="form-control" type="password" name="password_confirmation"
                                   placeholder="Enter your repeat password">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>Referral</label>
                            <input class="form-control" type="text" placeholder="Enter your Phone"
                                   name="referral" value="{{old('referral')}} {{$referral}}"/>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <strong>ReCaptcha:</strong>

                                <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>

                                @if ($errors->has('g-recaptcha-response'))

                                    <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>

                                @endif

                            </div>

                        </div>

                    </div>

                    <div class="col-12">
                        <button class="default-btn register" type="submit">
                            Sign up
                        </button>
                    </div>



                    <div class="col-12">
                        <p class="create">Already have an account? <a href="{{route('login')}}">Sign in</a></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- End User Area -->

<div class="dark-bar">
    <a href="#" class="d-flex align-items-center">
        <span class="dark-title">Enable Dark Theme</span>
    </a>

    <div class="form-check form-switch">
        <input type="checkbox" class="checkbox" id="darkSwitch">
    </div>
</div>

<!-- Start Go Top Area -->
<div class="go-top">
    <i class="ri-arrow-up-s-fill"></i>
    <i class="ri-arrow-up-s-fill"></i>
</div>
<!-- End Go Top Area -->

<!-- Jquery Min JS -->
<script src="{{asset('dashboard/user/js/jquery.min.js')}}"></script>
<!-- Bootstrap Bundle Min JS -->
<script src="{{asset('dashboard/user/js/bootstrap.bundle.min.js')}}"></script>
<!-- Owl Carousel Min JS -->
<script src="{{asset('dashboard/user/js/owl.carousel.min.js')}}"></script>
<!-- Metismenu Min JS -->
<script src="{{asset('dashboard/user/js/metismenu.min.js')}}"></script>
<!-- Simplebar Min JS -->
<script src="{{asset('dashboard/user/js/simplebar.min.js')}}"></script>
<!-- mixitup Min JS -->
<script src="{{asset('dashboard/user/js/mixitup.min.js')}}"></script>
<!-- Dark Mode Switch Min JS -->
<script src="{{asset('dashboard/user/js/dark-mode-switch.min.js')}}"></script>
<!-- Form Validator Min JS -->
<script src="{{asset('dashboard/user/js/form-validator.min.js')}}"></script>
<!-- Contact JS -->
<script src="{{asset('dashboard/user/js/contact-form-script.js')}}"></script>
<!-- Ajaxchimp Min JS -->
<script src="{{asset('dashboard/user/js/ajaxchimp.min.js')}}"></script>
<!-- Custom JS -->
<script src="{{asset('dashboard/user/js/custom.js')}}"></script>
</body>
</html>
