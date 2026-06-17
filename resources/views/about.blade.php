@extends('layouts.public')

@section('title', 'About')

@section('content')
    <section class="features-overview" id="features-section">
        <div class="content-header">
            <h2>About the COVID-19 Immunisation Registry</h2>
            <h6 class="section-subtitle text-muted">The official vaccination records management platform for the Republic of Zambia.</h6>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="mb-5">
                    <h4 class="font-weight-semibold">Overview</h4>
                    <p class="text-muted">
                        The COVID-19 Immunisation Registry is a comprehensive system developed by the
                        Ministry of Health, Republic of Zambia, to manage and track COVID-19 vaccination
                        records across the country. The system supports the national vaccination campaign
                        by providing a secure, centralized platform for vaccination data management.
                    </p>
                </div>

                <div class="mb-5">
                    <h4 class="font-weight-semibold">Purpose</h4>
                    <p class="text-muted">This registry serves as the official platform for:</p>
                    <ul class="text-muted">
                        <li>Recording COVID-19 vaccination information for all citizens and residents</li>
                        <li>Generating digital vaccination certificates with QR codes</li>
                        <li>Tracking vaccination coverage and progress nationwide</li>
                        <li>Enabling citizens to securely access and verify their vaccination status</li>
                        <li>Supporting public health surveillance and planning</li>
                        <li>Facilitating international travel requirements</li>
                    </ul>
                </div>

                <div class="mb-5">
                    <h4 class="font-weight-semibold">Key Features</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6><i class="fas fa-shield-alt text-info mr-2"></i>Secure Certificate Generation</h6>
                                <p class="text-muted" style="font-size: 13px;">Digital certificates with QR codes for easy and instant verification at borders and institutions.</p>
                            </div>
                            <div class="mb-3">
                                <h6><i class="fas fa-mobile-alt text-info mr-2"></i>OTP Verification</h6>
                                <p class="text-muted" style="font-size: 13px;">Secure access to vaccination records via email or SMS one-time passwords.</p>
                            </div>
                            <div class="mb-3">
                                <h6><i class="fas fa-hospital text-info mr-2"></i>Multi-facility Support</h6>
                                <p class="text-muted" style="font-size: 13px;">Integration with health facilities across all 10 provinces of Zambia.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6><i class="fas fa-chart-line text-info mr-2"></i>Real-time Tracking</h6>
                                <p class="text-muted" style="font-size: 13px;">Dashboard for monitoring vaccination progress and coverage targets.</p>
                            </div>
                            <div class="mb-3">
                                <h6><i class="fas fa-lock text-info mr-2"></i>Data Security</h6>
                                <p class="text-muted" style="font-size: 13px;">Protected health information with role-based access controls and encryption.</p>
                            </div>
                            <div class="mb-3">
                                <h6><i class="fas fa-qrcode text-info mr-2"></i>QR Code Verification</h6>
                                <p class="text-muted" style="font-size: 13px;">Instant certificate verification using QR code scanning technology.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h4 class="font-weight-semibold">Approved Vaccines</h4>
                    <p class="text-muted">The Ministry of Health has approved the following COVID-19 vaccines for emergency use in Zambia:</p>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <i class="fas fa-syringe fa-2x text-info mb-2"></i>
                                <h6>AstraZeneca</h6>
                                <small class="text-muted">2 doses required</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <i class="fas fa-syringe fa-2x text-success mb-2"></i>
                                <h6>Janssen (J&J)</h6>
                                <small class="text-muted">1 dose required</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <i class="fas fa-syringe fa-2x text-warning mb-2"></i>
                                <h6>Sinopharm</h6>
                                <small class="text-muted">2 doses required</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <i class="fas fa-syringe fa-2x text-primary mb-2"></i>
                                <h6>Pfizer</h6>
                                <small class="text-muted">2 doses required</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <i class="fas fa-syringe fa-2x text-danger mb-2"></i>
                                <h6>Moderna</h6>
                                <small class="text-muted">2 doses required</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-4 mb-4" style="background-color: #f3f7fb; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-3"><i class="fas fa-building mr-2"></i>Ministry of Health</h5>
                    <p class="text-muted" style="font-size: 13px;">
                        Ndeke House, Haile Selassie Avenue<br>
                        P.O. Box 30205, Lusaka 10101<br>
                        Republic of Zambia
                    </p>
                    <hr>
                    <p class="text-muted" style="font-size: 13px;">
                        <i class="fas fa-globe mr-2"></i><a href="https://www.moh.gov.zm" target="_blank">www.moh.gov.zm</a><br>
                        <i class="fas fa-envelope mr-2"></i><a href="mailto:info@moh.gov.zm">info@moh.gov.zm</a><br>
                        <i class="fas fa-phone mr-2"></i>+260 211 253 040
                    </p>
                </div>

                <div class="p-4 mb-4" style="background-color: #f3f7fb; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-3"><i class="fas fa-link mr-2"></i>Quick Links</h5>
                    <ul class="list-unstyled" style="font-size: 14px;">
                        <li class="mb-2"><a href="{{ url('get_vaccination_certificate') }}"><i class="fas fa-arrow-right mr-2 text-info"></i>Get Certificate</a></li>
                        <li class="mb-2"><a href="{{ url('verify_vaccination_certificate') }}"><i class="fas fa-arrow-right mr-2 text-info"></i>Verify Certificate</a></li>
                        <li class="mb-2"><a href="{{ url('vaccination-centres') }}"><i class="fas fa-arrow-right mr-2 text-info"></i>Vaccination Centres</a></li>
                        <li class="mb-2"><a href="{{ url('help') }}"><i class="fas fa-arrow-right mr-2 text-info"></i>FAQ's</a></li>
                        <li class="mb-2"><a href="{{ url('contact') }}"><i class="fas fa-arrow-right mr-2 text-info"></i>Contact Us</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
