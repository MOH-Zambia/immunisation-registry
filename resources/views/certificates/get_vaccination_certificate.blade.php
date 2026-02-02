<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    <style>
        .send {
            height: 48px;
        }
    </style>
</head>
<body>
<main>
    <article>
        <!-- Start Multiform HTML -->
        <section class="multi_step_form">
            <form id="msform">
                @csrf

                <!-- Tittle -->
                <div class="tittle">
                    <img src="{{ url('img/android-icon-96x96.png') }}" alt="Coat of Arms" style="opacity: .8"> <br><br>
                    <h2>Get Your Vaccination Certificate</h2>
                    <p>Follow these simple steps to securely access your COVID-19 vaccination certificate. This process ensures your information is protected.</p>
                </div>

                <!-- progressbar -->
                <ul id="progressbar">
                    <li class="active">ID Type</li>
                    <li>Personal Details</li>
                    <li>Verification</li>
                </ul>

                <!-- fieldsets -->
                <fieldset id="id_type_fieldset">
                    <h3>Step 1: Select Your ID Type</h3>
                    <h6>Choose the type of identification document you used during vaccination registration.</h6>
                    <select id="id_type" class="product_select mb-xl-5">
                        <option value="nrc" data-display="National Registration Card">National Registration Card (NRC)</option>
                        <option value="passport">Passport</option>
                        <option value="drivers_license">Driver's License</option>
                    </select>
                    <div class="alert alert-info mt-3" style="font-size: 12px; padding: 8px; position: relative; clear: both; margin-top: 15px;">
                        <strong>Tip:</strong> Select the same ID type you provided when you received your vaccination.
                    </div>
                    <button type="button" class="next action-button mt-xl-5">Continue</button>
                </fieldset>
                <fieldset id="personal_details_fieldset">
                    <h3>Step 2: Enter Your Personal Details</h3>
                    <h6>Please enter your information exactly as it was provided during vaccination. All fields are required.</h6>
                    <div class="form-group">
                        <input id="nrc" type="text" class="form-control" placeholder="National Registration Card Number (e.g., 123456/78/9)">
                        <input id="passport" type="text" class="form-control" placeholder="Passport Number">
                        <input id="drivers_license" type="text" class="form-control" placeholder="Driver's License Number">
                    </div>
                    <div class="form-group">
                        <input id="last_name" type="text" class="form-control" placeholder="Last Name (Surname)">
                    </div>
                    <div class="form-group">
                        <input id="first_name" type="text" class="form-control" placeholder="First Name (Given Name)">
                    </div>
                    <div class="form-group">
                        <input id="other_names" type="text" class="form-control" placeholder="Other Names (Middle Names - Optional)">
                    </div>
                    <div class="alert alert-warning mt-3" style="font-size: 12px; padding: 8px;">
                        <strong>Important:</strong> Enter your details exactly as they appear on your vaccination record. Names are case-sensitive.
                    </div>
                    <input type="hidden" id="client_id" name="client_id">
                    <button type="button" class="action-button previous previous_button">Back</button>
                    <button id="verify_personal_details" type="button" class="action-button">Verify Details</button>
                    <button id="personal_details" type="button" class="next action-button">Continue</button>
                </fieldset>
                <fieldset id="verification_fieldset">
                    <h3>Step 3: Verify Your Identity</h3>
                    <h6>Choose how you'd like to receive your One-Time Password (OTP)</h6>
                    <select id="verification_method" class="product_select mb-xl-5">
                        <option value="phone" data-display="By Phone (SMS)">By Phone (SMS)</option>
                        <!-- <option value="email">By Email</option> --> <!-- Option omitted, email verification broken -->
                    </select>

                    <div id="verification_method_phone" class="row">
                        <div class="col-12">
                            <div class="alert alert-info" style="font-size: 12px; padding: 8px; margin-bottom: 15px;">
                                <strong>Instructions:</strong>
                                <ol style="margin: 5px 0 0 0; padding-left: 20px;">
                                    <li>Click "Send" to receive a 4-digit code via SMS</li>
                                    <li>Check your phone for the verification code</li>
                                    <li>Enter the 4-digit code below</li>
                                    <li>Code is valid for 10 minutes</li>
                                </ol>
                            </div>
                        </div>
                        <div class="form-group col-md-9">
                            <input id="contact_number" type="text" class="form-control" disabled placeholder="Your phone number">
                        </div>
                        <div class="form-group col-md-3">
                            <button id="send_sms" type="button" class="send action-button">Send OTP</button>
                        </div>

                        <div class="done_text">
                        </div>
                    </div>

                    <div id="verification_method_email" class="row">
                        <div class="col-12">
                            <div class="alert alert-info" style="font-size: 12px; padding: 8px; margin-bottom: 15px;">
                                <strong>Instructions:</strong>
                                <ol style="margin: 5px 0 0 0; padding-left: 20px;">
                                    <li>Click "Send" to receive a verification code via email</li>
                                    <li>Check your inbox (and spam folder)</li>
                                    <li>Enter the 4-digit code below</li>
                                    <li>Code is valid for 10 minutes</li>
                                </ol>
                            </div>
                        </div>
                        <div class="form-group col-md-9">
                            <input id="contact_email_address" type="email" class="form-control" disabled placeholder="Your email address">
                        </div>
                        <div class="form-group col-md-3">
                            <button id="send_email" type="button" class="send action-button">Send OTP</button>
                        </div>

                        <div class="done_text">
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <h6>Enter your 4-digit verification code below:</h6>
                    </div>

                    <div class="code_group">
                        <input id="code1" type="text" class="form-control" placeholder="*" size=2 onInput="numericValuesOnly(this)" onKeyup="autotab(this, code1, code2)" maxlength=1 aria-label="First digit">
                        <input id="code2" type="text" class="form-control" placeholder="*" size=2 onInput="numericValuesOnly(this)" onKeyup="autotab(this, code1, code3)" maxlength=1 aria-label="Second digit">
                        <input id="code3" type="text" class="form-control" placeholder="*" size=2 onInput="numericValuesOnly(this)" onKeyup="autotab(this, code2, code4)" maxlength=1 aria-label="Third digit">
                        <input id="code4" type="text" class="form-control" placeholder="*" size=2 onInput="numericValuesOnly(this)" onKeyup="autotab(this, code3, finish)" maxlength=1 aria-label="Fourth digit">
                    </div>

                    <div class="alert alert-warning mt-3" style="font-size: 12px; padding: 8px;">
                        <strong>Didn't receive the code?</strong>
                        <ul style="margin: 5px 0 0 0; padding-left: 20px;">
                            <li>Wait 1-2 minutes for SMS delivery</li>
                            <li>Check your phone's message inbox</li>
                            <li>Verify you entered the correct phone number</li>
                            <li>Try requesting a new code</li>
                        </ul>
                    </div>

                    <button type="button" class="action-button previous previous_button">Back</button>
                    <button id="finish" type="button" class="action-button">Verify & Get Certificate</button>
{{--                    <a href="#" class="action-button">Finish</a>--}}
                </fieldset>
            </form>
        </section>
        <!-- END Multiform HTML -->
    </article>
</main>

<!-- Modal -->
<div class="modal fade" id="verificationErrorModal" tabindex="-1" role="dialog" aria-labelledby="verificationErrorModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verificationErrorModalTitle">Verification Failed</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Your details were not found in our database.</strong></p>
                <p>Please verify that:</p>
                <ul>
                    <li>You entered your ID number correctly</li>
                    <li>Your names match your vaccination record exactly</li>
                    <li>You selected the correct ID type</li>
                    <li>You have been vaccinated and registered in the system</li>
                </ul>
                <p class="mt-3">If you continue to experience issues, please contact the helpdesk at <strong>909</strong> or visit your nearest health facility.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Try Again</button>
            </div>
        </div>
    </div>
</div>


<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
{{--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/js/intlTelInput.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js'></script>

<script type="text/javascript">
    (function($) {
        "use strict";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#verify_personal_details").click(function (event) {
            event.preventDefault();
            var data = {};

            if ($("#id_type").val() === "nrc"){
                data = {
                    "nrc": $("#nrc").val(),
                    "last_name": $("#last_name").val(),
                    "first_name": $("#first_name").val(),
                    "other_names": $("#other_names").val()
                };
            }

            if ($("#id_type").val() === "passport"){
                data = {
                    "passport": $("#passport").val(),
                    "last_name": $("#last_name").val(),
                    "first_name": $("#first_name").val(),
                    "other_names": $("#other_names").val()
                };
            }

            if ($("#id_type").val() === "drivers_license"){
                data = {
                    "drivers_license": $("#drivers_license").val(),
                    "last_name": $("#last_name").val(),
                    "first_name": $("#first_name").val(),
                    "other_names": $("#other_names").val()
                };
            }

            $.ajax({
                url: "{{ route('clients.verify') }}",
                type:"POST",
                data: data,
                success: function(response){
                    var client = JSON.parse(response.message);

                    $("#contact_number").val(client.contact_number);
                    $("#contact_email_address").val(client.contact_email_address);
                    $("#client_id").val(client.id);

                    $("#verify_personal_details").hide();
                    $(".next").show();
                },
                error: function(error) {
                    // console.log(error);
                    $("#verificationErrorModal").modal('show');
                }
            });
        });

        //* Form js
        function verificationForm(){
            //jQuery time
            var current_fs, next_fs, previous_fs; //fieldsets
            var left, opacity, scale; //fieldset properties which we will animate
            var animating; //flag to prevent quick multi-click glitches

            $(".next").click(function () {
                if (animating)
                    return false;
                animating = true;

                current_fs = $(this).parent();
                next_fs = $(this).parent().next();

                //activate next step on progressbar using the index of next_fs
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                //show the next fieldset
                next_fs.show();

                //hide the current fieldset with style
                current_fs.animate(
                    { opacity: 0},
                    {
                        step: function (now, mx) {
                            //as the opacity of current_fs reduces to 0 - stored in "now"
                            //1. scale current_fs down to 80%
                            scale = 1 - (1 - now) * 0.2;
                            //2. bring next_fs from the right(50%)
                            left = (now * 50) + "%";
                            //3. increase opacity of next_fs to 1 as it moves in
                            opacity = 1 - now;
                            current_fs.css({
                                'transform': 'scale(' + scale + ')',
                                'position': 'absolute'
                            });
                            next_fs.css({
                                'left': left,
                                'opacity': opacity
                            });
                        },
                        duration: 800,
                        complete: function () {
                            if(next_fs.attr("id") === "personal_details_fieldset"){
                                // $(".next").prop("disabled",true);
                                $(".next").hide();
                            } else {
                                $(".next").show();
                            }
                            current_fs.hide();
                            animating = false;
                        },
                        //this comes from the custom easing plugin
                        easing: 'easeInOutBack'
                    }
                );

            });

            $(".previous").click(function () {
                if (animating)
                    return false;

                animating = true;

                current_fs = $(this).parent();
                previous_fs = $(this).parent().prev();

                //de-activate current step on progressbar
                $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

                //show the previous fieldset
                previous_fs.show();

                //hide the current fieldset with style
                current_fs.animate(
                    { opacity: 0 },
                    {
                        step: function (now, mx) {
                            //as the opacity of current_fs reduces to 0 - stored in "now"
                            //1. scale previous_fs from 80% to 100%
                            scale = 0.8 + (1 - now) * 0.2;
                            //2. take current_fs to the right(50%) - from 0%
                            left = ((1 - now) * 50) + "%";
                            //3. increase opacity of previous_fs to 1 as it moves in
                            opacity = 1 - now;
                            current_fs.css({
                                'left': left
                            });
                            previous_fs.css({
                                'transform': 'scale(' + scale + ')',
                                'opacity': opacity
                            });
                        },
                        duration: 800,
                        complete: function () {
                            if(previous_fs.attr("id") === "personal_details_fieldset"){
                                $(".next").hide();
                            } else {
                                $(".next").show();
                            }
                            current_fs.hide();
                            animating = false;
                        },
                        //this comes from the custom easing plugin
                        easing: 'easeInOutBack'
                    }
                );
            });

            $(".submit").click(function () {
                return false;
            })
        };

        function toggleInputIDType() {
            if ($("#id_type").val() === "nrc") {
                $("#nrc").show();
            } else {
                $("#nrc").hide();
            }
            if ($("#id_type").val() === "passport") {
                $("#passport").show();
            } else {
                $("#passport").hide();
            }
            if ($("#id_type").val() === "drivers_license") {
                $("#drivers_license").show();
            } else {
                $("#drivers_license").hide();
            }
        }

        toggleInputIDType();

        $("#id_type").change(function () {
            toggleInputIDType();
        });

        function toggleInputVerificationMethod() {
            if ($("#verification_method").val() === "phone") {
                $("#verification_method_phone").show();
            } else {
                $("#verification_method_phone").hide();
            }
            if ($("#verification_method").val() === "email") {
                $("#verification_method_email").show();
            } else {
                $("#verification_method_email").hide();
            }
        }

        toggleInputVerificationMethod();

        $("#verification_method").change(function () {
            toggleInputVerificationMethod();
        });

        //* Add Phone no select
        function phoneNoselect(){
            if ( $('#msform').length ){
                $("#phone").intlTelInput();
                $("#phone").intlTelInput("setNumber", "+260");
            };
        };

        //* Select js
        function nice_Select(){
            if ( $('.product_select').length ){
                $('select').niceSelect();
            };
        };

        $("#send_sms").click(function (event){
            event.preventDefault();

            $.ajax({
                url: "{{ route('sendSMS') }}",
                type:"POST",
                data: { "contact_number": $("#contact_number").val() },
                success: function(response){
                    // console.log(response);
                    $(".done_text").html('<a href="#" class="done_icon"><i class="ion-android-done"></i></a><h6><strong>Success!</strong> A 4-digit verification code has been sent to your phone via SMS.<br>Please check your messages and enter the code below.</h6>');
                },
                error: function(error) {
                    // console.log(error);
                    let errorMsg = error.responseJSON && error.responseJSON.message ? error.responseJSON.message : 'An error occurred';
                    $(".done_text").html('<a href="#" class="cancel_icon"><i class="ion-android-cancel"></i></a><h6><strong>Failed to send verification code.</strong><br>' + errorMsg + '<br>Please check your phone number and try again, or contact the helpdesk at <strong>909</strong>.</h6>');
                }
            });
        });

        $("#send_email").click(function (event){
            event.preventDefault();

            $.ajax({
                url: "{{ route('sendEmail') }}",
                type:"POST",
                data: { "contact_email_address": $("#contact_email_address").val() },
                success: function(response){
                    // console.log(response);
                    $(".done_text").html('<a href="#" class="done_icon"><i class="ion-android-done"></i></a><h6><strong>Success!</strong> A 4-digit verification code has been sent to your email.<br>Please check your inbox (and spam folder) and enter the code below.</h6>');
                },
                error: function(error) {
                    // console.log(error);
                    let errorMsg = error.responseJSON && error.responseJSON.message ? error.responseJSON.message : 'An error occurred';
                    $(".done_text").html('<a href="#" class="cancel_icon"><i class="ion-android-cancel"></i></a><h6><strong>Failed to send verification code.</strong><br>' + errorMsg + '<br>Please check your email address and try again, or contact the helpdesk at <strong>909</strong>.</h6>');
                }
            });

        });

        $("#finish").click(function (event){
            event.preventDefault();

            let OTP = $("#code1").val() + $("#code2").val() + $("#code3").val() + $("#code4").val();
            let client_id = $("#client_id").val();

            if (OTP.length !== 4) {
                alert("Please enter the complete 4-digit verification code.");
                return;
            }

            $.ajax({
                url: "{{ route('verifyOTP') }}",
                type: "POST",
                data: { "client_id": client_id, "OTP": OTP },
                success: function (response) {
                    // console.log(response);
                    location.href = response.message;
                },
                error: function (error) {
                    // console.log(error);
                    let errorMsg = error.responseJSON && error.responseJSON.message ? error.responseJSON.message : 'Invalid verification code';
                    alert("Verification Failed: " + errorMsg + "\n\nPlease check your code and try again. If the problem persists, request a new code or contact the helpdesk at 909.");
                }
            });
        });

        /*Function Calls*/
        verificationForm ();
        phoneNoselect ();
        nice_Select ();
    })(jQuery);

    function numericValuesOnly(elmnt){
        elmnt.value = elmnt.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')
    }

    window.onload = function() {
        document.getElementById('code1').value = '';
        document.getElementById('code2').value = '';
        document.getElementById('code3').value = '';
        document.getElementById('code4').value = '';
    }

    function autotab(original, destPrev, destNext){
        var key = event.key
        if (original.getAttribute && original.value.length==original.getAttribute("maxlength")) {
            // if (key === "Backspace" || key === "Delete") {
            //     original.value = '';
            //     destPrev.focus()
            // } else {
            //     destNext.value = '';
            //     destNext.focus()
            // }
            destNext.value = '';
            destNext.focus()
        }
    }

</script>

</body>
</html>
