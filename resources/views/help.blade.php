@extends('layouts.public')

@section('title', 'Help & FAQ')

@section('content')
    <section class="features-overview">
        <div class="content-header">
            <h2>Help & Frequently Asked Questions</h2>
            <h6 class="section-subtitle text-muted">Find answers to common questions about the COVID-19 Immunisation Registry.</h6>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div id="accordion">
                    <!-- FAQ 1 -->
                    <div class="mb-3">
                        <div class="p-3" style="background-color: #f3f7fb; border-radius: 8px; cursor: pointer;" data-toggle="collapse" data-target="#collapse1">
                            <h6 class="mb-0 font-weight-semibold">
                                <i class="fas fa-question-circle text-info mr-2"></i>
                                How do I get my COVID-19 vaccination certificate?
                                <i class="fas fa-chevron-down float-right mt-1"></i>
                            </h6>
                        </div>
                        <div id="collapse1" class="collapse show" data-parent="#accordion">
                            <div class="p-3 text-muted">
                                To get your vaccination certificate:
                                <ol>
                                    <li>Visit the homepage and click on "Get Vaccination Certificate"</li>
                                    <li>Select your ID type (NRC, Passport, or Driver's License)</li>
                                    <li>Enter your ID number, last name, and first name</li>
                                    <li>Request an OTP (One-Time Password) via SMS or Email</li>
                                    <li>Enter the OTP received to verify your identity</li>
                                    <li>Download or print your certificate</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="mb-3">
                        <div class="p-3" style="background-color: #f3f7fb; border-radius: 8px; cursor: pointer;" data-toggle="collapse" data-target="#collapse2">
                            <h6 class="mb-0 font-weight-semibold">
                                <i class="fas fa-question-circle text-info mr-2"></i>
                                What if I didn't receive the OTP?
                                <i class="fas fa-chevron-down float-right mt-1"></i>
                            </h6>
                        </div>
                        <div id="collapse2" class="collapse" data-parent="#accordion">
                            <div class="p-3 text-muted">
                                If you didn't receive the OTP:
                                <ul>
                                    <li>Check your spam/junk folder if you requested via email</li>
                                    <li>Ensure you entered the correct phone number or email address</li>
                                    <li>Wait a few minutes before requesting again (max 3 attempts per minute)</li>
                                    <li>Try an alternative method (switch from SMS to Email or vice versa)</li>
                                    <li>Contact support at <a href="mailto:support@moh.gov.zm">support@moh.gov.zm</a> if the issue persists</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="mb-3">
                        <div class="p-3" style="background-color: #f3f7fb; border-radius: 8px; cursor: pointer;" data-toggle="collapse" data-target="#collapse3">
                            <h6 class="mb-0 font-weight-semibold">
                                <i class="fas fa-question-circle text-info mr-2"></i>
                                How do I verify a vaccination certificate?
                                <i class="fas fa-chevron-down float-right mt-1"></i>
                            </h6>
                        </div>
                        <div id="collapse3" class="collapse" data-parent="#accordion">
                            <div class="p-3 text-muted">
                                To verify a vaccination certificate:
                                <ol>
                                    <li>Click on "Verify Vaccination Certificate" on the homepage</li>
                                    <li>Scan the QR code on the certificate using your device camera</li>
                                    <li>Or manually enter the certificate UUID (found on the certificate)</li>
                                    <li>The system will display the certificate details and verification status</li>
                                </ol>
                                <p>A valid certificate will show a green "Valid" status with the holder's details.</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="mb-3">
                        <div class="p-3" style="background-color: #f3f7fb; border-radius: 8px; cursor: pointer;" data-toggle="collapse" data-target="#collapse4">
                            <h6 class="mb-0 font-weight-semibold">
                                <i class="fas fa-question-circle text-info mr-2"></i>
                                What information do I need to retrieve my certificate?
                                <i class="fas fa-chevron-down float-right mt-1"></i>
                            </h6>
                        </div>
                        <div id="collapse4" class="collapse" data-parent="#accordion">
                            <div class="p-3 text-muted">
                                You will need:
                                <ul>
                                    <li>Valid identification number (NRC, Passport, or Driver's License)</li>
                                    <li>Your last name (surname) as registered during vaccination</li>
                                    <li>Your first name as registered during vaccination</li>
                                    <li>Access to your registered phone number or email for OTP verification</li>
                                </ul>
                                <p><strong>Important:</strong> Use the same details you provided at the vaccination centre.</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="mb-3">
                        <div class="p-3" style="background-color: #f3f7fb; border-radius: 8px; cursor: pointer;" data-toggle="collapse" data-target="#collapse5">
                            <h6 class="mb-0 font-weight-semibold">
                                <i class="fas fa-question-circle text-info mr-2"></i>
                                Is my personal information secure?
                                <i class="fas fa-chevron-down float-right mt-1"></i>
                            </h6>
                        </div>
                        <div id="collapse5" class="collapse" data-parent="#accordion">
                            <div class="p-3 text-muted">
                                Yes, your information is secure. The system:
                                <ul>
                                    <li>Uses encrypted connections (HTTPS/SSL) for all data transmission</li>
                                    <li>Implements role-based access control for authorized personnel only</li>
                                    <li>Follows Zambia's data protection regulations</li>
                                    <li>Requires OTP verification before certificate access</li>
                                    <li>Maintains audit logs of all data access</li>
                                    <li>Stores data on secure government servers within Zambia</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 6 -->
                    <div class="mb-3">
                        <div class="p-3" style="background-color: #f3f7fb; border-radius: 8px; cursor: pointer;" data-toggle="collapse" data-target="#collapse6">
                            <h6 class="mb-0 font-weight-semibold">
                                <i class="fas fa-question-circle text-info mr-2"></i>
                                How long is my vaccination certificate valid?
                                <i class="fas fa-chevron-down float-right mt-1"></i>
                            </h6>
                        </div>
                        <div id="collapse6" class="collapse" data-parent="#accordion">
                            <div class="p-3 text-muted">
                                Certificate validity depends on:
                                <ul>
                                    <li>The type of vaccine received</li>
                                    <li>Number of doses completed (fully vaccinated status)</li>
                                    <li>Current national and international health guidelines</li>
                                </ul>
                                <p>Your certificate will display the expiration date if applicable. Check with your destination country for specific travel requirements.</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 7 -->
                    <div class="mb-3">
                        <div class="p-3" style="background-color: #f3f7fb; border-radius: 8px; cursor: pointer;" data-toggle="collapse" data-target="#collapse7">
                            <h6 class="mb-0 font-weight-semibold">
                                <i class="fas fa-question-circle text-info mr-2"></i>
                                What if my certificate has incorrect details?
                                <i class="fas fa-chevron-down float-right mt-1"></i>
                            </h6>
                        </div>
                        <div id="collapse7" class="collapse" data-parent="#accordion">
                            <div class="p-3 text-muted">
                                If your certificate contains incorrect information:
                                <ol>
                                    <li>Visit the health facility where you were vaccinated</li>
                                    <li>Bring your original ID and vaccination card</li>
                                    <li>Request a correction of your records</li>
                                    <li>A new certificate will be generated with the corrected details</li>
                                </ol>
                                <p>You can also contact us at <a href="mailto:support@moh.gov.zm">support@moh.gov.zm</a> with your details and a copy of your ID.</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 8 -->
                    <div class="mb-3">
                        <div class="p-3" style="background-color: #f3f7fb; border-radius: 8px; cursor: pointer;" data-toggle="collapse" data-target="#collapse8">
                            <h6 class="mb-0 font-weight-semibold">
                                <i class="fas fa-question-circle text-info mr-2"></i>
                                Which vaccines are approved in Zambia?
                                <i class="fas fa-chevron-down float-right mt-1"></i>
                            </h6>
                        </div>
                        <div id="collapse8" class="collapse" data-parent="#accordion">
                            <div class="p-3 text-muted">
                                MOH has approved the following COVID-19 vaccines for emergency use:
                                <ul>
                                    <li><strong>AstraZeneca (Vaxzevria)</strong> - 2 doses, 8-12 weeks apart</li>
                                    <li><strong>Janssen (Johnson & Johnson)</strong> - Single dose</li>
                                    <li><strong>Sinopharm (BIBP)</strong> - 2 doses, 3-4 weeks apart</li>
                                    <li><strong>Pfizer-BioNTech (Comirnaty)</strong> - 2 doses, 3-4 weeks apart</li>
                                    <li><strong>Moderna (Spikevax)</strong> - 2 doses, 4 weeks apart</li>
                                </ul>
                                <p>All approved vaccines are provided free of charge to eligible persons.</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 9 -->
                    <div class="mb-3">
                        <div class="p-3" style="background-color: #f3f7fb; border-radius: 8px; cursor: pointer;" data-toggle="collapse" data-target="#collapse9">
                            <h6 class="mb-0 font-weight-semibold">
                                <i class="fas fa-question-circle text-info mr-2"></i>
                                Who can I contact for help?
                                <i class="fas fa-chevron-down float-right mt-1"></i>
                            </h6>
                        </div>
                        <div id="collapse9" class="collapse" data-parent="#accordion">
                            <div class="p-3 text-muted">
                                For assistance, you can reach us through:
                                <ul>
                                    <li>Email: <a href="mailto:support@moh.gov.zm">support@moh.gov.zm</a></li>
                                    <li>Phone: +260 211 253 344</li>
                                    <li>Toll Free: 909</li>
                                    <li>Visit your nearest health facility</li>
                                    <li>Use the <a href="{{ url('contact') }}">Contact Us</a> page</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-4 mb-4" style="background-color: #f3f7fb; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-3"><i class="fas fa-certificate text-info mr-2"></i>Get Certificate</h5>
                    <p class="text-muted" style="font-size: 13px;">Access your COVID-19 vaccination certificate securely online.</p>
                    <a href="{{ url('get_vaccination_certificate') }}" class="btn btn-info btn-sm btn-block">Get Started</a>
                </div>

                <div class="p-4 mb-4" style="background-color: #f3f7fb; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-3"><i class="fas fa-check-circle text-success mr-2"></i>Verify Certificate</h5>
                    <p class="text-muted" style="font-size: 13px;">Verify the authenticity of a vaccination certificate.</p>
                    <a href="{{ url('verify_vaccination_certificate') }}" class="btn btn-success btn-sm btn-block">Verify Now</a>
                </div>

                <div class="p-4 mb-4" style="background-color: #f3f7fb; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-3"><i class="fas fa-map-marker-alt text-warning mr-2"></i>Vaccination Centres</h5>
                    <p class="text-muted" style="font-size: 13px;">Find a vaccination centre near you across Zambia.</p>
                    <a href="{{ url('vaccination-centres') }}" class="btn btn-warning btn-sm btn-block">Find Centre</a>
                </div>

                <div class="p-4 mb-4" style="background-color: #f3f7fb; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-3"><i class="fas fa-headset text-danger mr-2"></i>Need More Help?</h5>
                    <p class="text-muted" style="font-size: 13px;">Contact our support team for personalized assistance.</p>
                    <a href="{{ url('contact') }}" class="btn btn-outline-danger btn-sm btn-block">Contact Support</a>
                </div>
            </div>
        </div>
    </section>
@endsection
