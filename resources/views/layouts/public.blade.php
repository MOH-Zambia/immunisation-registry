<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ministry of Health | @yield('title', 'Immunisation Registry')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
          integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
          crossorigin="anonymous"/>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.2.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <!-- Custom styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">

    @yield('styles')
</head>
<body id="body">
<header id="header-section">
    <nav class="navbar navbar-expand-lg pl-3 pl-sm-0" id="navbar">
        <div class="container">
            <div class="navbar-brand-wrapper d-flex w-100">
                <a href="{{ url('/') }}">
                    <img src="{{ url('img/log_moh.gif') }}" alt="Ministry of Health" style="max-height: 60px !important;">
                </a>
            </div>
            <div class="collapse navbar-collapse navbar-menu-wrapper" id="navbarSupportedContent">
                <ul class="navbar-nav align-items-lg-center align-items-start ml-auto">
                    <li class="d-flex align-items-center justify-content-between pl-4 pl-lg-0">
                        <div class="navbar-collapse-logo">
                            <img src="{{ url('img/Group2.svg') }}" alt="">
                        </div>
                        <button class="navbar-toggler close-button" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="mdi mdi-close navbar-toggler-icon pl-5"></span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('help') }}">FAQ's</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('vaccination-centres') }}">Centres</a>
                    </li>
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('dashboard') }}">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('login') }}">Login</a>
                            </li>
                        @endauth
                    @endif
                    <li class="nav-item btn-contact-us pl-4 pl-lg-0">
                        <a href="{{ url('contact') }}" class="btn btn-info">Contact Us</a>
                    </li>
                </ul>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="mdi mdi-menu navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
</header>

@yield('banner')

<div class="content-wrapper">
    <div class="container">
        @yield('content')
    </div>
</div>

<footer class="border-top">
    <div class="container">
        <div class="row py-4">
            <div class="col-md-4">
                <h6 class="font-weight-semibold">Ministry of Health</h6>
                <p class="text-muted" style="font-size: 13px;">
                    Republic of Zambia<br>
                    Ndeke House, Haile Selassie Avenue<br>
                    P.O. Box 30205, Lusaka
                </p>
            </div>
            <div class="col-md-4">
                <h6 class="font-weight-semibold">Quick Links</h6>
                <ul class="list-unstyled" style="font-size: 13px;">
                    <li><a href="{{ url('get_vaccination_certificate') }}" class="text-muted">Get Certificate</a></li>
                    <li><a href="{{ url('verify_vaccination_certificate') }}" class="text-muted">Verify Certificate</a></li>
                    <li><a href="{{ url('help') }}" class="text-muted">FAQ's</a></li>
                    <li><a href="{{ url('contact') }}" class="text-muted">Contact Us</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="font-weight-semibold">Contact</h6>
                <ul class="list-unstyled text-muted" style="font-size: 13px;">
                    <li><i class="fas fa-phone mr-2"></i> +260 211 253 040</li>
                    <li><i class="fas fa-envelope mr-2"></i> info@moh.gov.zm</li>
                    <li><i class="fas fa-globe mr-2"></i> <a href="https://www.moh.gov.zm" class="text-muted">www.moh.gov.zm</a></li>
                </ul>
            </div>
        </div>
        <p class="text-center text-muted pt-2 border-top">Copyright &copy; {{ date('Y') }} <a href="https://www.moh.gov.zm" class="px-1">Ministry of Health</a> All rights reserved.</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init();
</script>
@yield('scripts')
</body>
</html>
