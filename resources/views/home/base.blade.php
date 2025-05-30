<!doctype html>
<html lang="zxx">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Links of CSS files -->
    <link rel="stylesheet" href="{{asset('home/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('home/css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('home/css/meanmenu.min.css')}}">
    <link rel="stylesheet" href="{{asset('home/css/magnific-popup.min.css')}}">
    <link rel="stylesheet" href="{{asset('home/css/odometer.min.css')}}">
    <link rel="stylesheet" href="{{asset('home/css/showMoreItems-theme.min.css')}}">
    <link rel="stylesheet" href="{{asset('home/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('home/css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('home/css/remixicon.css')}}">
    <link rel="stylesheet" href="{{asset('home/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('home/css/responsive.css')}}">

    <meta name="og:title" content="{{$siteName}}"/>
    <meta name="og:type" content="company"/>
    <meta name="og:url" content="/"/>
    <meta name="og:image" content="{{asset('home/images/'.$web->logo)}}"/>
    <meta name="og:site_name" content="{{$siteName}}"/>
    <meta name="og:description" content="Comprehensive financial advice and investment services that are tailored to meet your individual needs."/>
    <meta name="description" content="{{$web->description}}">
    <meta name="keywords" content="business, marketing, agency">
    <title> {{$siteName}} | {{$pageName}}</title>
    <!-- favicons Icons -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('home/images/'.$web->logo)}}" />
    @stack('css')
</head>
<body>
@inject('injected','App\Defaults\Custom')

<!-- Start Top Header Area -->
<div class="top-header-area">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-4 col-md-4">
                <ul class="top-header-social-links d-flex align-items-center">
                    <li>Follow us on:</li>
                    <li><a href="#" target="_blank"><i class="ri-facebook-fill"></i></a></li>
                    <li><a href="#" target="_blank"><i class="ri-twitter-fill"></i></a></li>
                    <li><a href="#" target="_blank"><i class="ri-linkedin-fill"></i></a></li>
                    <li><a href="#" target="_blank"><i class="ri-instagram-line"></i></a></li>
                </ul>
            </div>

            <div class="col-lg-8 col-md-8">
                <ul class="top-header-contact-info">
                    <li><i class="ri-time-line"></i><span>SUN - SAT:</span> 24/7</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End Top Header Area -->

<!-- Start Navbar Area -->
<div class="navbar-area">
    <div class="enry-responsive-nav">
        <div class="container">
            <div class="enry-responsive-menu">
                <div class="logo">
                    <a href="{{url('/')}}"><img src="{{asset('home/images/'.$web->logo)}}" style="width: 100px;" alt="logo"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="enry-nav">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-md navbar-light">
                <a class="navbar-brand" href="{{url('/')}}"><img src="{{asset('home/images/'.$web->logo)}}"
                                                            style="width: 100px;" alt="logo"></a>

                <div class="collapse navbar-collapse mean-menu">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="{{url('/')}}" class="nav-link">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('about')}}" class="nav-link">About</a>
                        </li>

                        <li class="nav-item"><a href="#" class="dropdown-toggle nav-link">Pages</a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a href="{{url('plans')}}" class="nav-link">Plans</a></li>
                                <li class="nav-item"><a href="{{url('faqs')}}" class="nav-link">Frequently Asked Questions</a></li>
                                <li class="nav-item"><a href="{{url('terms')}}" class="nav-link">Terms & Conditions</a></li>
                                <li class="nav-item"><a href="{{url('privacy')}}" class="nav-link">Privacy policy</a></li>
                            </ul>
                        </li>

                        <li class="nav-item"><a href="#" class="dropdown-toggle nav-link">Services</a>
                            <ul class="dropdown-menu">
                                @foreach($injected->getServices() as $service)
                                    <li class="nav-item"><a href="{{route('service.details',['id'=>$service->id])}}" class="nav-link">{{$service->title}}</a></li>
                                @endforeach
                                <li class="nav-item"><a href="{{ route('home.retirement') }}" class="nav-link">Retirement and IRAs</a></li>
                            </ul>
                        </li>

                        <li class="nav-item"><a href="#" class="dropdown-toggle nav-link">Account</a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a href="{{route('login')}}" class="nav-link">Login</a></li>

                                <li class="nav-item"><a href="{{route('register')}}" class="nav-link">Register</a></li>
                            </ul>
                        </li>

                        <li class="nav-item"><a href="{{url('contact')}}" class="nav-link">Contact</a></li>
                    </ul>

                </div>
            </nav>
        </div>
    </div>
</div>
<!-- End Navbar Area -->

@yield('content')

<!-- Start Footer Area -->
<footer class="footer-area">
    <div class="container">
        <div class="row  justify-content-center">
            <div class="col-lg-3 col-sm-6 col-md-6">
                <div class="single-footer-widget">
                    <a href="{{url('/')}}" class="logo">
                        <img src="{{asset('home/images/'.$web->logo)}}" alt="image">
                    </a>
                    <div class="footer-contact-info">
                        <h5>Contact:</h5>
                        <ul>
                            @if(!empty($web->phone))
                                <li><span>Call:</span> <a href="tel:{{$web->phone}}">{{$web->phone}}</a></li>
                            @endif
                            <li><span>Email:</span> <a href="mailto:{{$web->email}}">{{$web->email}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
                <div class="single-footer-widget pl-4">
                    <h3>Quick Links</h3>
                    <ul class="links-list">
                        <li><a href="{{url('about')}}">About us</a></li>
                        <li><a href="{{url('contact')}}">Contact Us</a></li>
                        <li><a href="{{url('plans')}}">Plans</a></li>
                        <li><a href="{{url('faqs')}}">FAQs</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
                <div class="single-footer-widget pl-2">
                    <h3>Services</h3>
                    <ul class="links-list">
                        <li><a href="{{ route('home.retirement') }}" >Retirement and IRAs</a></li>
                        @foreach($injected->getServices() as $service)
                            <li><a href="{{route('service.details',['id'=>$service->id])}}">{{$service->title}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
    <div class="copyright-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-7 col-sm-6">
                    <p>Copyright @ 2012 - {{date('Y')}} {{$siteName}} </p>
                </div>
                <div class="col-lg-6 col-md-5 col-sm-6">
                    <ul class="social-links">
                        <li><a href="#" target="_blank"><i class="ri-facebook-fill"></i></a></li>
                        <li><a href="#" target="_blank"><i class="ri-twitter-fill"></i></a></li>
                        <li><a href="#" target="_blank"><i class="ri-linkedin-fill"></i></a></li>
                        <li><a href="#" target="_blank"><i class="ri-instagram-line"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- End Footer Area -->

<div class="go-top"><i class="ri-arrow-up-s-line"></i></div>

<!-- Links of JS files -->
<script src="{{asset('home/js/jquery.min.js')}}"></script>
<script src="{{asset('home/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('home/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('home/js/appear.min.js')}}"></script>
<script src="{{asset('home/js/odometer.min.js')}}"></script>
<script src="{{asset('home/js/magnific-popup.min.js')}}"></script>
<script src="{{asset('home/js/mixitup.min.js')}}"></script>
<script src="{{asset('home/js/meanmenu.min.js')}}"></script>
<script src="{{asset('home/js/showMoreItems.min.js')}}"></script>
<script src="{{asset('home/js/wow.min.js')}}"></script>
<script src="{{asset('home/js/form-validator.min.js')}}"></script>
<script src="{{asset('home/js/contact-form-script.js')}}"></script>
<script src="{{asset('home/js/ajaxchimp.min.js')}}"></script>
<script src="{{asset('home/js/main.js')}}"></script>
<!-- Google language start -->
<style>

    #google_translate_element {
        z-index: 9999999;
        position: fixed;
        bottom: 25px;
        left: 15px;
    }

    .goog-te-gadget {
        font-family: Roboto, "Open Sans", sans-serif !important;
        text-transform: uppercase;
    }
    .goog-te-gadget-simple
    {
        padding: 0px !important;
        line-height: 1.428571429;
        color: white;
        vertical-align: middle;
        background-color: black;
        border: 1px solid #a5a5a599;
        border-radius: 4px;
        float: right;
        margin-top: -4px;
        z-index: 999999;
    }
    .goog-te-banner-frame.skiptranslate
    {
        display: none !important;
        color: white;
    }
    .goog-te-gadget-icon
    {
        background: none !important;
        display: none;
        color: white;
    }
    .goog-te-gadget-simple .goog-te-menu-value
    {
        font-size: 12px;
        color: white;
        font-family: 'Open Sans' , sans-serif;
    }
</style>
<div id="google_translate_element"></div>
<script type="text/javascript">
    window.onload = function googleTranslateElementInit() {
        new google.translate.TranslateElement({ pageLanguage: 'en' }, 'google_translate_element');
    }
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<!-- start popup massage -->
<div class="notifier" style="display: none;">
    <div class="txt" style="color:black;">While you are waiting,someone from <b></b> just traded with <a href="javascript:void(0);" onclick="javascript:void(0);"></a></div>
</div>
<style>
    .notifier{
        border-radius: 7px;
        position: fixed;
        z-index: 90;
        bottom: 80px;
        right: 50px;
        background: #fff;
        padding: 15px 40px;
        box-shadow: 0px 5px 13px 0px rgba(0,0,0,.3);
    }
    .notifier a {
        font-weight: 700;
        display: block;
        color:#0080FF;
    }
    .notifier a, .notifier a:active {
        transition: all .2s ease;
        color:#0080FF;
    }
</style>
<script data-cfasync="false" src="#"></script><script type="text/javascript">
    var listCountries = ['Germany', 'Spain', 'Russia', 'Italy',
        'Italy',  'United States', 'Egypt',
        'United Kingdom', "United States","England","Germany","Germany","United States","Switzerland",
        "Austria","Austria","Austria","Australia","Australia","Australia","Russia","Russia",
        "United States","United Kingdom","Spain","Spain","India","England","Italy","Ukraine"
    ];
    var listPlans = ['$500','$5000','$1,000','$1000','$550','$3000','$690', '$360',
        '$700', '$600',"$500","$700","$1,000","$1289","$5000","$7000","$10000"];
    interval = Math.floor(Math.random() * (40000 - 8000 + 1) + 8000);
    var run = setInterval(request, interval);

    function request() {
        clearInterval(run);
        interval = Math.floor(Math.random() * (40000 - 8000 + 1) + 8000);
        var country = listCountries[Math.floor(Math.random() * listCountries.length)];
        var plan = listPlans[Math.floor(Math.random() * listPlans.length)];
        var msg = 'While you are still contemplating ,an investor from <b>' + country + '</b> ' +
            'just traded with <a href="javascript:void(0);" onclick="javascript:void(0);">' + plan + ' .</a>';
        $(".notifier .txt").html(msg);
        $(".notifier").stop(true).fadeIn(300);
        window.setTimeout(function() {
            $(".notifier").stop(true).fadeOut(300);
        }, 6000);
        run = setInterval(request, interval);
    }
</script>
<!-- end popup massage -->
@stack('js')
<!-- Smartsupp Live Chat script -->
<!-- Smartsupp Live Chat script -->
<script type="text/javascript">
    var _smartsupp = _smartsupp || {};
    _smartsupp.key = '49c6b2b55482a2a46b9ff69afed8e17cb259585d';
    window.smartsupp||(function(d) {
        var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
        s=d.getElementsByTagName('script')[0];c=d.createElement('script');
        c.type='text/javascript';c.charset='utf-8';c.async=true;
        c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
    })(document);
</script>
<noscript> Powered by <a href=“https://www.smartsupp.com” target=“_blank”>Smartsupp</a></noscript>
</body>
</html>
