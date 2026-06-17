@extends('layouts.public')

@section('title', 'Contact Us')

@section('content')
    <section class="features-overview">
        <div class="content-header">
            <h2>Contact Us</h2>
            <h6 class="section-subtitle text-muted">Get in touch with the Ministry of Health for any queries about the Immunisation Registry.</h6>
        </div>

        <div class="row">
            <div class="col-md-7">
                <div class="p-4 mb-4" style="background-color: #fff; border: 1px solid #e8e8e8; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-4">Send us a Message</h5>

                    <div id="contactSuccess" class="alert alert-success" style="display: none;">
                        <i class="fas fa-check-circle mr-2"></i>Thank you for your message. We will respond within 2-3 business days.
                    </div>

                    <form id="contactForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="e.g. +260 97X XXX XXX">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject">Subject <span class="text-danger">*</span></label>
                                    <select class="form-control" id="subject" name="subject" required>
                                        <option value="">Select a subject...</option>
                                        <option value="Certificate Issue">Certificate Issue</option>
                                        <option value="OTP Not Received">OTP Not Received</option>
                                        <option value="Incorrect Details">Incorrect Details on Certificate</option>
                                        <option value="Technical Issue">Technical Issue</option>
                                        <option value="General Inquiry">General Inquiry</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message" name="message" rows="5" placeholder="Describe your issue or inquiry in detail..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-info btn-block">
                            <i class="fas fa-paper-plane mr-2"></i>Send Message
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-md-5">
                <div class="p-4 mb-4" style="background-color: #f3f7fb; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-3"><i class="fas fa-building mr-2"></i>Office Address</h5>
                    <p class="text-muted">
                        Ministry of Health<br>
                        Ndeke House, Haile Selassie Avenue<br>
                        P.O. Box 30205<br>
                        Lusaka 10101, Zambia
                    </p>
                </div>

                <div class="p-4 mb-4" style="background-color: #f3f7fb; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-3"><i class="fas fa-phone mr-2"></i>Phone</h5>
                    <p class="text-muted">
                        Main Line: +260 211 253 344<br>
                        Toll Free: 909
                    </p>
                </div>

                <div class="p-4 mb-4" style="background-color: #f3f7fb; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-3"><i class="fas fa-envelope mr-2"></i>Email</h5>
                    <p class="text-muted">
                        General: <a href="mailto:info@moh.gov.zm">info@moh.gov.zm</a><br>
                        Support: <a href="mailto:support@moh.gov.zm">support@moh.gov.zm</a>
                    </p>
                </div>

                <div class="p-4 mb-4" style="background-color: #f3f7fb; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-3"><i class="fas fa-clock mr-2"></i>Office Hours</h5>
                    <p class="text-muted">
                        Monday - Friday: 08:00 - 17:00<br>
                        Saturday: 08:00 - 13:00<br>
                        Sunday & Holidays: Closed
                    </p>
                </div>

                <div class="p-4 mb-4" style="background-color: #f3f7fb; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-3"><i class="fas fa-globe mr-2"></i>Online</h5>
                    <p class="text-muted">
                        Website: <a href="https://www.moh.gov.zm" target="_blank">www.moh.gov.zm</a><br>
                        Registry: <a href="{{ url('/') }}">ir.moh.gov.zm</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        document.getElementById('contactSuccess').style.display = 'block';
        this.reset();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>
@endsection
