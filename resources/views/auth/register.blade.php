<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            <form id="msform">

                <!-- Tittle -->
                <div class="tittle">
                    <img src="{{ url('img/android-icon-96x96.png') }}" alt="Coat of Arms" style="opacity: .8"> <br><br>
                    <h2>Registration</h2>
                    <p>In order to use this service, you have to complete this registration process</p>
                </div>

                <fieldset>
                    <h6>Please enter your personnal information provided during vaccination</h6>
                    <div class="form-group">
{{--                        <label for="id_number">ID</label>--}}
                        <input id="id_number" type="text" class="form-control" placeholder="ID Number">
                    </div>
                    <div class="form-group">
{{--                        <label for="vaccination_code">Vaccination Code</label>--}}
                        <input id="vaccination_code" type="text" class="form-control" placeholder="Vaccination Code">
                    </div>
                    <div class="form-group">
{{--                        <label for="last_name">Last Name</label>--}}
                        <input id="last_name" type="text" class="form-control" placeholder="Last Name">
                    </div>
                    <div class="form-group">
{{--                        <label for="first_name">First Name</label>--}}
                        <input id="first_name" type="text" class="form-control" placeholder="First Name">
                    </div>
                    <div class="form-group">
{{--                        <label for="other_names">Other Names</label>--}}
                        <input id="other_names" type="text" class="form-control" placeholder="Other Names">
                    </div>
                    <button type="button" class="action-button previous_button">Back</button>
                    <button type="button" class="next action-button">Continue</button>
                </fieldset>

            </form>
        </section>
        <!-- END Multiform HTML -->
    </article>
</main>

<footer class="credit">Author: Hyser - Distributed By: <a title="Awesome web design code & scripts" href="https://www.codehim.com?source=demo-page" target="_blank">CodeHim</a></footer>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/js/intlTelInput.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js'></script>

</body>
</html>
