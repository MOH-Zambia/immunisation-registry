<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} | Get Vaccination Certificate</title>
    <!-- Normalize CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <!-- Bootstrap 4 CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css'>
    <!-- Telephone Input CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/css/intlTelInput.css'>
    <!-- Icons CSS -->
    <link rel='stylesheet' href='https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css'>
    <!-- Nice Select CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css'>
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <!-- Demo CSS -->
    <link rel="stylesheet" href="{{ asset('css/demo.css') }}">

</head>
<body>
<main>
    <article>
        <!-- Start Multiform HTML -->
        <section class="multi_step_form">
            <form id="msform">

                <!-- Tittle -->
                <div class="tittle">
                    <img src="{{ url('img/android-icon-96x96.png') }}" alt="Coat of Arms" style="opacity: .8"> <br><br>
                    <h2>Verification Process</h2>
                    <p>In order to get your vaccination certificate, you have to complete this verification process</p>
                </div>

                <!-- progressbar -->
                <ul id="progressbar">
                    <li class="active">ID Type</li>
                    <li>Personal Details</li>
                    <li>Verification</li>
                </ul>

                <!-- fieldsets -->
                <fieldset>
                    <h3>Select ID Type</h3>
                    <h6>Please upload any of these documents to verify your Identity.</h6>
                    <select id="id_type" class="product_select mb-xl-5">
                        <option value="nrc" data-display="National Registration Card">National Registration Card</option>
                        <option value="passport">Passport</option>
                        <option value="drivers_license">Drivers License</option>
                    </select>

                    <button type="button" class="action-button previous previous_button mt-xl-5">Back</button>
                    <button type="button" class="next action-button mt-xl-5">Continue</button>
                </fieldset>
                <fieldset>
                    <h6>Please enter your personnal information provided during vaccination</h6>
                    <div class="form-group">
                        <input id="nrc" type="text" class="form-control" placeholder="National Registration Card Number">
                        <input id="passport" type="text" class="form-control" placeholder="Passport Number">
                        <input id="drivers_license" type="text" class="form-control" placeholder="Drivers License Number">
                    </div>
                    <div class="form-group">
                        <input id="vaccination_code" type="text" class="form-control" placeholder="Vaccination Code">
                    </div>
                    <div class="form-group">
                        <input id="last_name" type="text" class="form-control" placeholder="Last Name">
                    </div>
                    <div class="form-group">
                        <input id="first_name" type="text" class="form-control" placeholder="First Name">
                    </div>
                    <div class="form-group">
                        <input id="other_names" type="text" class="form-control" placeholder="Other Names">
                    </div>
                    <button type="button" class="action-button previous previous_button">Back</button>
                    <button id="personal_details" type="button" class="next action-button">Continue</button>
                </fieldset>
                <fieldset>
                    <h3>Select verification method</h3>
                    <select id="verification_method" class="product_select mb-xl-5">
                        <option value="phone" data-display="By Phone">By Phone</option>
                        <option value="email">By Email</option>
                    </select>

                    <div id="verification_method_phone" class="row">
                        <h6>We will send you a SMS. Input the code to verify.</h6>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <input type="tel" id="phone" class="form-control" placeholder="+260">
                            </div>
                            <div class="form-group col-md-6">
                                <input type="text" class="form-control" placeholder="09XXXXXXX">
                            </div>
                            <div class="form-group col-md-3">
                                <a href="#" class="action-button">Send</a>
                            </div>
                        </div>

                        <div class="done_text">
                            <a href="#" class="don_icon"><i class="ion-android-done"></i></a>
                            <h6>A secret code is sent to your phone. <br>Please enter it here.</h6>
                        </div>
                    </div>

                    <div id="verification_method_email" class="row">
                        <h6>We will send you an email. Input the code to verify.</h6>
                        <div class="row">
                            <div class="form-group col-md-9">
                                <input type="email" id="email" class="form-control" placeholder="john@example.com">
                            </div>
                            <div class="form-group col-md-3">
                                <a href="#" class="action-button">Send</a>
                            </div>
                        </div>
                        <div class="done_text">
                            <a href="#" class="don_icon"><i class="ion-android-done"></i></a>
                            <h6>A secret code is sent to your phone. <br>Please enter it here.</h6>
                        </div>
                    </div>

                    <div class="code_group">
                        <input type="text" class="form-control" placeholder="0">
                        <input type="text" class="form-control" placeholder="0">
                        <input type="text" class="form-control" placeholder="0">
                        <input type="text" class="form-control" placeholder="0">
                    </div>

                    <button type="button" class="action-button previous previous_button">Back</button>
                    <a href="#" class="action-button">Finish</a>
                </fieldset>
            </form>
        </section>
        <!-- END Multiform HTML -->
    </article>
</main>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
{{--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/js/intlTelInput.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js'></script>


<script>
    // global app configuration object
    var config = {
        routes: {
            verify: "{{ route('client.verify') }}"
        }
    };
</script>
<script src="{{ asset('js/register.js') }}"></script>

</body>
</html>
