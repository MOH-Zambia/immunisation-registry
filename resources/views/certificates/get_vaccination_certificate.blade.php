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
                <fieldset id="id_type_fieldset">
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
                <fieldset id="personal_details_fieldset">
                    <h6>Please enter your personnal information provided during vaccination</h6>
                    <div class="form-group">
                        <input id="nrc" type="text" class="form-control" placeholder="National Registration Card Number">
                        <input id="passport" type="text" class="form-control" placeholder="Passport Number">
                        <input id="drivers_license" type="text" class="form-control" placeholder="Drivers License Number">
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
                    <button id="verify_personal_details" type="button" class="action-button">Verify</button>
                    <button id="personal_details" type="button" class="next action-button">Continue</button>
                </fieldset>
                <fieldset id="verification_fieldset">
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
                                <button id="otp" type="button" class="next action-button">Send</button>
{{--                                <a href="#" class="action-button">Send</a>--}}
                            </div>
                        </div>

                        <div class="done_text">
                        </div>
                    </div>

                    <div id="verification_method_email" class="row">
                        <h6>We will send you an email. Input the code to verify.</h6>
                        <div class="row">
                            <div class="form-group col-md-9">
                                <input type="email" id="email" class="form-control" placeholder="john@example.com">
                            </div>
                            <div class="form-group col-md-3">
                                <button id="send" type="button" class="action-button">Send</button>
                            </div>
                        </div>
                        <div class="done_text">
                        </div>
                    </div>

                    <div class="code_group">
                        <input id="code1" type="text" class="form-control" placeholder="0">
                        <input id="code2" type="text" class="form-control" placeholder="0">
                        <input id="code3" type="text" class="form-control" placeholder="0">
                        <input id="code4" type="text" class="form-control" placeholder="0">
                    </div>

                    <button type="button" class="action-button previous previous_button">Back</button>
                    <button id="finish" type="button" class="action-button">Finish</button>
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
                <h5 class="modal-title" id="verificationErrorModalTitle">Verification failed</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Your details were not found in the database
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
                    if(response.verification === "Successful") {
                        // console.log(response);
                        $(".next").show();
                    }
                    if(response.verification === "Unsuccessful"){
                        // console.log(response);
                        $('#verificationErrorModal').modal('show');
                    }
                },
                error: function(error) {
                    // console.log(error);
                    $('#verificationErrorModal').modal('show');
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

        $("#send").click(function (event){
            event.preventDefault();
            var data = {};

            if ($("#verification_method").val() === "email") {
                if ($("#id_type").val() === "nrc") {
                    data = {
                        "nrc": $("#nrc").val(),
                        "email": $("#email").val()
                    };
                }
                if ($("#id_type").val() === "passport") {
                    data = {
                        "passport": $("#passport").val(),
                        "email": $("#email").val()
                    };
                }
                if ($("#id_type").val() === "drivers_license") {
                    data = {
                        "drivers_license": $("#drivers_license").val(),
                        "email": $("#email").val()
                    };
                }

                $.ajax({
                    url: "{{ route('sendOTP') }}",
                    type:"POST",
                    data: data,
                    success: function(response){
                        // console.log(response);
                        if(response.message === "Successful") {
                            $(".done_text").html('<a href="#" class="done_icon"><i class="ion-android-done"></i></a><h6>A verification code is sent to your email. <br>Please enter it here.</h6>');
                        }
                        if(response.message === "Unsuccessful"){
                            $(".done_text").html('<a href="#" class="cancel_icon"><i class="ion-android-cancel"></i></a><h6>Failed to send a verification code to your email. <br>Please try again.</h6>');
                        }
                    },
                    error: function(error) {
                        $(".done_text").html('<a href="#" class="don_icon"><i class="ion-android-cancel"></i></a><h6>Failed to send a verification code to your email. <br>Please try again.</h6>');
                    }
                });
            }
        });

        $("#finish").click(function (event){
            event.preventDefault();
            var data = {};

            if ($("#verification_method").val() === "email") {
                let OTP = $("#code1").val() + $("#code2").val() + $("#code3").val() + $("#code4").val();

                if ($("#id_type").val() === "nrc") {
                    data = {
                        "nrc": $("#nrc").val(),
                        "email": $("#email").val(),
                        "OTP": OTP
                    };
                }
                if ($("#id_type").val() === "passport") {
                    data = {
                        "passport": $("#passport").val(),
                        "email": $("#email").val(),
                        "OTP": OTP
                    };
                }
                if ($("#id_type").val() === "drivers_license") {
                    data = {
                        "drivers_license": $("#drivers_license").val(),
                        "email": $("#email").val(),
                        "OTP": OTP
                    };
                }

                $.ajax({
                    url: "{{ route('verifyOTP') }}",
                    type: "POST",
                    data: data,
                    success: function (response) {
                        console.log(response);
                        if (response.message === "Wrong OTP") {
                            alert("Wrong OTP");
                            return false;
                        }
                    },
                    error: function (error) {

                    }
                });
            }
        });


        /*Function Calls*/
        verificationForm ();
        phoneNoselect ();
        nice_Select ();
    })(jQuery);
</script>

</body>
</html>
