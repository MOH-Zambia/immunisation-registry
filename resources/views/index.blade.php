<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Ministry of Health | Immunisation Registry</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
          integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
          crossorigin="anonymous"/>

    <link rel="stylesheet" href="https://cdn.boomcdn.com/libs/owl-carousel/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdn.boomcdn.com/libs/owl-carousel/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.2.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <!-- leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>

    <!-- Custom styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">
</head>
<body id="body" data-spy="scroll" data-target=".navbar" data-offset="100">
<header id="header-section">
    <div class="alert-bar aos-init aos-animate" data-aos="fade-down" data-aos-duration="500">
        <div class="container">
            <div class="row">
                <div class="col-md-12 content">
                    <p class="text fa-pull-left">
                        <span tabindex="0">Covid-19 Vaccine Registration and Appointment Request</span>
                        <a class="alert-link" target="_blank" href="https://ir.moh.gov.zm/registration" tabindex="0">Click Here</a>
                    </p>
                    <a href="javascript:void(0);" class="alert-close-icon fa-pull-right" aria-label="Close Notification bar" tabindex="0">
                        <span class="sr-only sr-only-focusable">Close Notification bar</span>
                        <i class="fas fa-times"></i>
                    </a>
                    <!-- <i class="fa fa-close alert-close-icon"></i> -->
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg pl-3 pl-sm-0" id="navbar">
        <div class="container">
            <div class="navbar-brand-wrapper d-flex w-100">
                <img src="{{url('img/apple-icon-72x72.png')}}" alt="Zambian coat of arms">
            </div>
            <div class="collapse navbar-collapse navbar-menu-wrapper" id="navbarSupportedContent">
                <ul class="navbar-nav align-items-lg-center align-items-start ml-auto">
                    <li class="d-flex align-items-center justify-content-between pl-4 pl-lg-0">
                        <div class="navbar-collapse-logo">
                            <img src="{{url('img/Group2.svg')}}" alt="">
                        </div>
                        <button class="navbar-toggler close-button" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="mdi mdi-close navbar-toggler-icon pl-5"></span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#header-section">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features-section">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#digital-marketing-section">FAQ's</a>
                    </li>
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('login') }}">Login</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('register') }}">Register</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                    <li class="nav-item btn-contact-us pl-4 pl-lg-0">
                        <button class="btn btn-info" data-toggle="modal" data-target="#exampleModal">Contact Us</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<div class="banner mt-lg-5">
    <div class="container">
        <div class="info p-4">
{{--            <h4 class="font-weight-semibold">Ministry of Health<br>COVID-19 Vaccination Certification</h4>--}}
            <h5 class="font-weight-normal pb-3 py-3"><i class="fas fa-info-circle"></i> This is the official Zambia COVID-19 Immunisation Registry</h5>
            <ul>
                <li>Only people who are vaccinated can retrieve their COVID-19 Vaccine Digital Certificate</li>
                <li>MOH has approved AstraZeneca, Janssen (J&J) and Sinopharm COVID-19 vaccine for emergency use, as safe and effective</li>
                <li>MOH plans to provide the COVID-19 vaccine free of charge to all Zambian citizens and residents</li>
                <li>Certain population groups should receive the vaccine first because they are at higher risk. The three priority population groups are: older people, people with certain chronic conditions and key healthcare workers</li>
            </ul>
            <div style="text-align: center;">
                <a class="btn btn-opacity-light mr-1" href="{{ url('get_vaccination_certificate') }}">Get Vaccination Certificate</a>
                <a class="btn btn-opacity-success mr-1" href="{{ url('verify_vaccination_certificate') }}">Verify Vaccination Certificate</a>
            </div>
        </div>
    </div>
</div>
<div class="content-wrapper">
    <div class="container">
        <section class="features-overview" id="features-section" >
{{--            <div class="content-header">--}}
{{--                <h2>How does it works</h2>--}}
{{--                <h6 class="section-subtitle text-muted">One theme that serves as an easy-to-use operational toolkit<br>that meets customer's needs.</h6>--}}
{{--            </div>--}}
            <div class="d-md-flex justify-content-between">
                <div class="col-4">
                    <div class="features-width">
                        <img src="{{url('img/Group7.svg')}}" alt="" class="img-icons">
                        <h5 class="py-3">COVID-19 Vaccination <br>Programme</h5>
                        <p class="text-muted">The outbreak of the Coronavirus Disease (COVID-19) was declared a Public Health Emergency of International Concern (PHEIC) on 30 January 2020, by the Director-General of the World Health Organisation (WHO).</p>
                        <a href="#"><p class="readmore-link">Read more...</p></a>
                    </div>
                </div>
                <div class="col-4">
                    <div class="features-width">
                        <img src="{{url('img/Group5.svg')}}" alt="" class="img-icons">
                        <h5 class="py-3">COVID-19 Vaccination <br>Certificate</h5>
                        <p class="text-muted">Your proof of vaccination is your vaccine receipt or enhanced vaccine certificate with a QR code, sometimes called a vaccine passport or vaccine record. You can download an enhanced vaccine certificate with a QR code</p>
                        <a href="#"><p class="readmore-link">Read more...</p></a>
                    </div>
                </div>
                <div class="col-4">
                    <div class="features-width">
                        <img src="{{url('img/Group12.svg')}}" alt="" class="img-icons">
                        <h5 class="py-3">Vaccination <br>Centres</h5>
                        <p class="text-muted">Find a walk-in coronavirus (COVID-19) vaccination site</p>
                        <a href="#"><p class="readmore-link">Read more...</p></a>
                    </div>
                </div>
            </div>
        </section>



        <!-- Modal for Contact - us Button -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel">Contact Us</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="Name">Name</label>
                                <input type="text" class="form-control" id="Name" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label for="Email">Email</label>
                                <input type="email" class="form-control" id="Email-1" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label for="Message">Message</label>
                                <textarea class="form-control" id="Message" placeholder="Enter your Message"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="border-top">
    <p class="text-center text-muted pt-4">Copyright © 2021 <a href="https://www.moh.gov.zm" class="px-1">Ministry of Health</a>All rights reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script src="https://cdn.boomcdn.com/libs/owl-carousel/2.3.4/owl.carousel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

<!-- Custom Javascript -->
<script href="{{ asset('js/landingpage.js') }}" type="text/javascript"></script>
</body>
</html>
