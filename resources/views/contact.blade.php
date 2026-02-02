@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Contact Us</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item active">Contact</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Send us a Message</h3>
                        </div>
                        <div class="card-body">
                            <form id="contactForm">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone">
                                </div>
                                <div class="form-group">
                                    <label for="subject">Subject <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="subject" name="subject" required>
                                </div>
                                <div class="form-group">
                                    <label for="message">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Send Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Contact Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h5><i class="fas fa-building text-primary"></i> Address</h5>
                                <p>
                                    Ministry of Health<br>
                                    Ndeke House, Haile Selassie Avenue<br>
                                    P.O. Box 30205<br>
                                    Lusaka 10101, Zambia
                                </p>
                            </div>

                            <div class="mb-3">
                                <h5><i class="fas fa-phone text-primary"></i> Phone</h5>
                                <p>
                                    Main Line: +260-211-253-344<br>
                                    Toll Free: 909
                                </p>
                            </div>

                            <div class="mb-3">
                                <h5><i class="fas fa-envelope text-primary"></i> Email</h5>
                                <p>
                                    <a href="mailto:info@moh.gov.zm">info@moh.gov.zm</a><br>
                                    <a href="mailto:support@moh.gov.zm">support@moh.gov.zm</a>
                                </p>
                            </div>

                            <div class="mb-3">
                                <h5><i class="fas fa-globe text-primary"></i> Website</h5>
                                <p>
                                    <a href="https://www.moh.gov.zm" target="_blank">www.moh.gov.zm</a>
                                </p>
                            </div>

                            <div class="mb-3">
                                <h5><i class="fas fa-clock text-primary"></i> Office Hours</h5>
                                <p>
                                    Monday - Friday: 08:00 - 17:00<br>
                                    Saturday: 08:00 - 13:00<br>
                                    Sunday: Closed
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('page_scripts')
<script>
    $(document).ready(function() {
        $('#contactForm').on('submit', function(e) {
            e.preventDefault();

            // Here you would typically send the form data via AJAX
            alert('Thank you for contacting us. Your message has been received. We will get back to you shortly.');
            this.reset();
        });
    });
</script>
@endpush
