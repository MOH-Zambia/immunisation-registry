<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | Registration Page</title>
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
            <form id="msform" method="post" action="{{ route('register') }}">
                @csrf
                <!-- Tittle -->
                <div class="tittle">
                    <img src="{{ url('img/android-icon-96x96.png') }}" alt="Coat of Arms" style="opacity: .8"> <br><br>
                    <h2>Registration</h2>
                    <p>In order to use this service, you have to complete this registration process</p>
                </div>

                <!-- fieldsets -->
                <fieldset>
                    <h6>Please enter your personnal information provided during vaccination</h6>
                    <div class="form-group">
                        <select id="id_type" class="product_select">
                            <option value="nrc" data-display="National Registration Card">National Registration Card</option>
                            <option value="passport">Passport</option>
                            <option value="drivers_license">Drivers License</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input id="nrc" type="text" class="form-control" placeholder="National Registration Card Number">
                        <input id="passport" type="text" class="form-control" placeholder="Passport Number">
                        <input id="drivers_license" type="text" class="form-control" placeholder="Drivers License Number">
                    </div>
                    <div class="form-group">
                        <input id="email"
                               type="text"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="E-Mail Address">
                        @error('email')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input id="last_name"
                               type="text"
                               class="form-control @error('last_name') is-invalid @enderror"
                               value="{{ old('last_name') }}"
                               placeholder="Last Name">
                        @error('last_name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input id="first_name"
                               type="text"
                               class="form-control @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name') }}"
                               placeholder="First Name">
                        @error('first_name')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input id="other_names"
                               type="text"
                               class="form-control @error('other_names') is-invalid @enderror"
                               value="{{ old('other_names') }}"
                               placeholder="Other Names">
                        @error('other_names')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input id="password"
                               type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Password">
                        @error('password')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input id="password_confirm"
                               type="password"
                               name="password_confirm"
                               class="form-control"
                               placeholder="Retype password">
                    </div>
                    <input type="submit" class="action-button" value="Register"/>
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

        toggleInputIDType();

        $('#id_type').change(function(){
            toggleInputIDType();
        });

        $('input[type=submit]').click(function(e) {
            e.preventDefault(); //prevent form submit when button is clicked

            var data = {};

            if ($("#id_type").val() === "nrc"){
                data = {
                    "nrc": $("#nrc").val(),
                    "last_name": $("#last_name").val(),
                    "first_name": $("#first_name").val(),
                    "other_names": $("#other_names").val(),
                    "password": $("#password").val(),
                    "password_confirm": $("#password_confirm").val()
                };
            }

            if ($("#id_type").val() === "passport"){
                data = {
                    "passport": $("#passport").val(),
                    "last_name": $("#last_name").val(),
                    "first_name": $("#first_name").val(),
                    "other_names": $("#other_names").val(),
                    "password": $("#password").val(),
                    "password_confirm": $("#password_confirm").val()
                };
            }

            if ($("#id_type").val() === "drivers_license"){
                data = {
                    "drivers_license": $("#drivers_license").val(),
                    "last_name": $("#last_name").val(),
                    "first_name": $("#first_name").val(),
                    "other_names": $("#other_names").val(),
                    "password": $("#password").val(),
                    "password_confirm": $("#password_confirm").val()
                };
            }

            $.ajax({
                url: "{{ route('api.users.register') }}",
                type:"POST",
                data: data,
                success:function(response){
                    if(response.verification === "successful") {
                        console.log(response);
                        $("#msform").submit(); //submit the form if details have been verified
                    }else{
                        console.log(response);
                        $('#verificationErrorModal').modal('show');
                    }
                },
                error: function(error) {
                    console.log(error);
                    $('#verificationErrorModal').modal('show');
                }
            });
        });

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
    })(jQuery);
</script>

</body>
</html>
