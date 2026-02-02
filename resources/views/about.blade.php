@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">About</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item active">About</li>
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
                            <h3 class="card-title">About the COVID-19 Immunisation Registry</h3>
                        </div>
                        <div class="card-body">
                            <h4>Overview</h4>
                            <p>
                                The COVID-19 Immunisation Registry is a comprehensive system developed by the
                                Ministry of Health, Republic of Zambia, to manage and track COVID-19 vaccination
                                records across the country.
                            </p>

                            <h4>Purpose</h4>
                            <p>
                                This registry serves as the official platform for:
                            </p>
                            <ul>
                                <li>Recording COVID-19 vaccination information</li>
                                <li>Generating digital vaccination certificates</li>
                                <li>Tracking vaccination coverage and progress</li>
                                <li>Enabling citizens to access and verify their vaccination status</li>
                                <li>Supporting public health surveillance and planning</li>
                            </ul>

                            <h4>Features</h4>
                            <ul>
                                <li><strong>Secure Certificate Generation:</strong> Digital certificates with QR codes for easy verification</li>
                                <li><strong>OTP Verification:</strong> Secure access to vaccination records via email or SMS</li>
                                <li><strong>Multi-facility Support:</strong> Integration with health facilities nationwide</li>
                                <li><strong>Real-time Tracking:</strong> Dashboard for monitoring vaccination progress</li>
                                <li><strong>Data Security:</strong> Protected health information with role-based access</li>
                            </ul>

                            <h4>Contact Information</h4>
                            <p>
                                <strong>Ministry of Health</strong><br>
                                Ndeke House, Haile Selassie Avenue<br>
                                P.O. Box 30205, Lusaka 10101<br>
                                Zambia<br>
                                <br>
                                Website: <a href="https://www.moh.gov.zm" target="_blank">www.moh.gov.zm</a><br>
                                Email: <a href="mailto:info@moh.gov.zm">info@moh.gov.zm</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
