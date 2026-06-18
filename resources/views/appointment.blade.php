@extends('layouts.public')

@section('title', 'Vaccination Appointment')

@section('content')
    <section class="features-overview">
        <div class="content-header">
            <h2>COVID-19 Vaccination Appointment</h2>
            <h6 class="section-subtitle text-muted">Register for a COVID-19 vaccination appointment at a centre near you.</h6>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="p-4 mb-4" style="background-color: #fff; border: 1px solid #e8e8e8; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-4"><i class="fas fa-calendar-check text-info mr-2"></i>Request an Appointment</h5>

                    <div id="appointmentSuccess" class="alert alert-success" style="display: none;">
                        <i class="fas fa-check-circle mr-2"></i><strong>Appointment Requested!</strong> You will receive a confirmation via SMS/Email with your appointment details.
                    </div>

                    <form id="appointmentForm">
                        @csrf

                        <!-- Step indicator -->
                        <div class="mb-4 pb-3 border-bottom">
                            <small class="text-muted"><i class="fas fa-user mr-1"></i> Step 1: Personal Information</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter first name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter last name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_birth">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender">Gender <span class="text-danger">*</span></label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="">Select gender...</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_type">ID Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="id_type" name="id_type" required>
                                        <option value="">Select ID type...</option>
                                        <option value="NRC">National Registration Card (NRC)</option>
                                        <option value="Passport">Passport</option>
                                        <option value="Drivers License">Driver's License</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_number">ID Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="id_number" name="id_number" placeholder="Enter your ID number" required>
                                </div>
                            </div>
                        </div>

                        <!-- Contact details -->
                        <div class="mb-4 mt-4 pb-3 border-bottom">
                            <small class="text-muted"><i class="fas fa-phone mr-1"></i> Step 2: Contact Details</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="e.g. 097XXXXXXX" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email (optional)">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="province">Province <span class="text-danger">*</span></label>
                                    <select class="form-control" id="province" name="province" required>
                                        <option value="">Select province...</option>
                                        <option value="Lusaka">Lusaka</option>
                                        <option value="Copperbelt">Copperbelt</option>
                                        <option value="Southern">Southern</option>
                                        <option value="Eastern">Eastern</option>
                                        <option value="Central">Central</option>
                                        <option value="Northern">Northern</option>
                                        <option value="Western">Western</option>
                                        <option value="North-Western">North-Western</option>
                                        <option value="Luapula">Luapula</option>
                                        <option value="Muchinga">Muchinga</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="district">District <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="district" name="district" placeholder="Enter your district" required>
                                </div>
                            </div>
                        </div>

                        <!-- Appointment preferences -->
                        <div class="mb-4 mt-4 pb-3 border-bottom">
                            <small class="text-muted"><i class="fas fa-calendar mr-1"></i> Step 3: Appointment Preferences</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="preferred_date">Preferred Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="preferred_date" name="preferred_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="preferred_time">Preferred Time <span class="text-danger">*</span></label>
                                    <select class="form-control" id="preferred_time" name="preferred_time" required>
                                        <option value="">Select time slot...</option>
                                        <option value="08:00-10:00">08:00 - 10:00</option>
                                        <option value="10:00-12:00">10:00 - 12:00</option>
                                        <option value="12:00-14:00">12:00 - 14:00</option>
                                        <option value="14:00-16:00">14:00 - 16:00</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vaccine_preference">Vaccine Preference</label>
                                    <select class="form-control" id="vaccine_preference" name="vaccine_preference">
                                        <option value="">No preference</option>
                                        <option value="AstraZeneca">AstraZeneca</option>
                                        <option value="Janssen">Janssen (Johnson & Johnson)</option>
                                        <option value="Sinopharm">Sinopharm</option>
                                        <option value="Pfizer">Pfizer</option>
                                        <option value="Moderna">Moderna</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dose_number">Dose Number <span class="text-danger">*</span></label>
                                    <select class="form-control" id="dose_number" name="dose_number" required>
                                        <option value="">Select dose...</option>
                                        <option value="1">1st Dose</option>
                                        <option value="2">2nd Dose</option>
                                        <option value="3">Booster Dose</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Health information -->
                        <div class="mb-4 mt-4 pb-3 border-bottom">
                            <small class="text-muted"><i class="fas fa-heartbeat mr-1"></i> Step 4: Health Information</small>
                        </div>

                        <div class="form-group">
                            <label>Do you have any of the following conditions?</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="condition_none" name="conditions[]" value="None">
                                        <label class="form-check-label" for="condition_none">None</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="condition_diabetes" name="conditions[]" value="Diabetes">
                                        <label class="form-check-label" for="condition_diabetes">Diabetes</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="condition_hypertension" name="conditions[]" value="Hypertension">
                                        <label class="form-check-label" for="condition_hypertension">Hypertension</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="condition_heart" name="conditions[]" value="Heart Disease">
                                        <label class="form-check-label" for="condition_heart">Heart Disease</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="condition_respiratory" name="conditions[]" value="Respiratory Disease">
                                        <label class="form-check-label" for="condition_respiratory">Respiratory Disease</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="condition_other" name="conditions[]" value="Other">
                                        <label class="form-check-label" for="condition_other">Other</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="allergies" name="allergies" value="1">
                                <label class="form-check-label" for="allergies">I have known allergies to vaccines or their components</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="consent" name="consent" value="1" required>
                                <label class="form-check-label" for="consent">
                                    I consent to receiving the COVID-19 vaccination and confirm the information provided is accurate. <span class="text-danger">*</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-info btn-block btn-lg mt-4">
                            <i class="fas fa-calendar-check mr-2"></i>Submit Appointment Request
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-4 mb-4" style="background-color: #e8f4fd; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-3"><i class="fas fa-info-circle text-info mr-2"></i>How It Works</h5>
                    <ol class="text-muted" style="font-size: 13px; padding-left: 18px;">
                        <li class="mb-2">Fill in your personal details and contact information</li>
                        <li class="mb-2">Select your preferred vaccination centre location</li>
                        <li class="mb-2">Choose a date and time slot that works for you</li>
                        <li class="mb-2">Submit your request and wait for confirmation</li>
                        <li class="mb-2">Receive confirmation via SMS or email</li>
                        <li class="mb-2">Visit the centre on your appointment date with your ID</li>
                    </ol>
                </div>

                <div class="p-4 mb-4" style="background-color: #f3f7fb; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-3"><i class="fas fa-clipboard-list text-success mr-2"></i>What to Bring</h5>
                    <ul class="text-muted list-unstyled" style="font-size: 13px;">
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Valid ID (NRC, Passport, or Driver's License)</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Appointment confirmation (SMS or printout)</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Vaccination card (if receiving 2nd dose)</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Face mask</li>
                    </ul>
                </div>

                <div class="p-4 mb-4" style="background-color: #fff3cd; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-3"><i class="fas fa-exclamation-triangle text-warning mr-2"></i>Important Notes</h5>
                    <ul class="text-muted list-unstyled" style="font-size: 13px;">
                        <li class="mb-2"><i class="fas fa-circle text-warning mr-2" style="font-size: 8px;"></i>Walk-in vaccinations are also available</li>
                        <li class="mb-2"><i class="fas fa-circle text-warning mr-2" style="font-size: 8px;"></i>Appointments are subject to vaccine availability</li>
                        <li class="mb-2"><i class="fas fa-circle text-warning mr-2" style="font-size: 8px;"></i>Arrive 15 minutes before your time slot</li>
                        <li class="mb-2"><i class="fas fa-circle text-warning mr-2" style="font-size: 8px;"></i>You will be observed for 15-30 mins after vaccination</li>
                    </ul>
                </div>

                <div class="p-4 mb-4" style="background-color: #f3f7fb; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-3"><i class="fas fa-map-marker-alt text-danger mr-2"></i>Find a Centre</h5>
                    <p class="text-muted" style="font-size: 13px;">Browse vaccination centres across all 10 provinces of Zambia.</p>
                    <a href="{{ url('vaccination-centres') }}" class="btn btn-outline-info btn-sm btn-block">View Vaccination Centres</a>
                </div>

                <div class="p-4 mb-4" style="background-color: #f3f7fb; border-radius: 10px;">
                    <h5 class="font-weight-semibold mb-3"><i class="fas fa-headset text-primary mr-2"></i>Need Help?</h5>
                    <p class="text-muted" style="font-size: 13px;">
                        <i class="fas fa-phone mr-1"></i> Toll Free: 909<br>
                        <i class="fas fa-envelope mr-1"></i> <a href="mailto:support@moh.gov.zm">support@moh.gov.zm</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Set minimum date to tomorrow
    var tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('preferred_date').min = tomorrow.toISOString().split('T')[0];

    // Set max date of birth (must be at least 12 years old)
    var maxDob = new Date();
    maxDob.setFullYear(maxDob.getFullYear() - 12);
    document.getElementById('date_of_birth').max = maxDob.toISOString().split('T')[0];

    // Handle "None" checkbox logic
    document.getElementById('condition_none').addEventListener('change', function() {
        if (this.checked) {
            document.querySelectorAll('input[name="conditions[]"]').forEach(function(cb) {
                if (cb.id !== 'condition_none') cb.checked = false;
            });
        }
    });

    document.querySelectorAll('input[name="conditions[]"]').forEach(function(cb) {
        if (cb.id !== 'condition_none') {
            cb.addEventListener('change', function() {
                if (this.checked) {
                    document.getElementById('condition_none').checked = false;
                }
            });
        }
    });

    // Form submission
    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Basic validation
        var consent = document.getElementById('consent');
        if (!consent.checked) {
            alert('You must provide consent to proceed with the appointment request.');
            return;
        }

        // Show success message
        document.getElementById('appointmentSuccess').style.display = 'block';
        this.reset();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>
@endsection
