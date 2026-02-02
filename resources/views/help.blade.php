@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Help & Support</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Help</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Frequently Asked Questions</h3>
                        </div>
                        <div class="card-body">
                            <div id="accordion">
                                <!-- FAQ 1 -->
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne">
                                                <i class="fas fa-question-circle"></i> How do I get my COVID-19 vaccination certificate?
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                        <div class="card-body">
                                            To get your vaccination certificate:
                                            <ol>
                                                <li>Visit the homepage and click on "Get Vaccination Certificate"</li>
                                                <li>Enter your NRC, Passport Number, Driver's License, or Email</li>
                                                <li>Request an OTP (One-Time Password) via SMS or Email</li>
                                                <li>Enter the OTP received to access your certificate</li>
                                                <li>Download or print your certificate</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <!-- FAQ 2 -->
                                <div class="card">
                                    <div class="card-header" id="headingTwo">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo">
                                                <i class="fas fa-question-circle"></i> What if I didn't receive the OTP?
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            If you didn't receive the OTP:
                                            <ul>
                                                <li>Check your spam/junk folder if you requested via email</li>
                                                <li>Ensure you entered the correct phone number or email address</li>
                                                <li>Wait a few minutes and try requesting again</li>
                                                <li>Contact support if the issue persists</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- FAQ 3 -->
                                <div class="card">
                                    <div class="card-header" id="headingThree">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree">
                                                <i class="fas fa-question-circle"></i> How do I verify a vaccination certificate?
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseThree" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            To verify a vaccination certificate:
                                            <ol>
                                                <li>Click on "Verify Vaccination Certificate" on the homepage</li>
                                                <li>Scan the QR code on the certificate using your device camera</li>
                                                <li>Or manually enter the certificate UUID</li>
                                                <li>The system will display the certificate details and verification status</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <!-- FAQ 4 -->
                                <div class="card">
                                    <div class="card-header" id="headingFour">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour">
                                                <i class="fas fa-question-circle"></i> What information do I need to register?
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseFour" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            For registration, you need:
                                            <ul>
                                                <li>Valid identification (NRC, Passport, or Driver's License)</li>
                                                <li>Full name as it appears on your ID</li>
                                                <li>Contact phone number</li>
                                                <li>Email address</li>
                                                <li>Date of birth</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- FAQ 5 -->
                                <div class="card">
                                    <div class="card-header" id="headingFive">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive">
                                                <i class="fas fa-question-circle"></i> Is my information secure?
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseFive" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            Yes, your information is secure. The system:
                                            <ul>
                                                <li>Uses encrypted connections (HTTPS/SSL)</li>
                                                <li>Implements role-based access control</li>
                                                <li>Follows data protection regulations</li>
                                                <li>Uses OTP verification for certificate access</li>
                                                <li>Maintains audit logs of all access</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- FAQ 6 -->
                                <div class="card">
                                    <div class="card-header" id="headingSix">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSix">
                                                <i class="fas fa-question-circle"></i> How long is my vaccination certificate valid?
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseSix" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            The validity of your vaccination certificate depends on:
                                            <ul>
                                                <li>The type of vaccine received</li>
                                                <li>Number of doses completed</li>
                                                <li>Current national and international guidelines</li>
                                                <li>Your certificate will show the expiration date if applicable</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- FAQ 7 -->
                                <div class="card">
                                    <div class="card-header" id="headingSeven">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSeven">
                                                <i class="fas fa-question-circle"></i> Who can I contact for technical support?
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseSeven" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            For technical support, you can:
                                            <ul>
                                                <li>Email: <a href="mailto:support@moh.gov.zm">support@moh.gov.zm</a></li>
                                                <li>Call: +260-211-253-344</li>
                                                <li>Toll Free: 909</li>
                                                <li>Visit your nearest health facility</li>
                                                <li>Use the <a href="{{ url('contact') }}">Contact Us</a> form</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-certificate"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Get Certificate</span>
                            <span class="info-box-number">Access your vaccination certificate</span>
                            <a href="{{ url('get_vaccination_certificate') }}" class="btn btn-sm btn-light mt-2">Get Started</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Verify Certificate</span>
                            <span class="info-box-number">Verify a vaccination certificate</span>
                            <a href="{{ url('verify_vaccination_certificate') }}" class="btn btn-sm btn-light mt-2">Verify Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-phone"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Contact Support</span>
                            <span class="info-box-number">Get help from our team</span>
                            <a href="{{ url('contact') }}" class="btn btn-sm btn-light mt-2">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('page_css')
<style>
    #accordion .btn-link {
        text-decoration: none;
        color: #333;
        font-weight: 500;
    }
    #accordion .btn-link:hover {
        color: #007bff;
    }
    #accordion .card-header {
        background-color: #f8f9fa;
    }
</style>
@endpush
