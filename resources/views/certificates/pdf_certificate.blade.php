<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Ministry of Health | Immunisation Registry</title>

    <!-- Fonts -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"> -->

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
          integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
          crossorigin="anonymous"/>

    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
          rel="stylesheet">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.5/css/adminlte.min.css"
          integrity="sha512-rVZC4rf0Piwtw/LsgwXxKXzWq3L0P6atiQKBNuXYRbg2FoRbSTIY0k2DxuJcs7dk4e/ShtMzglHKBOJxW8EQyQ=="
          crossorigin="anonymous"/>


    <!-- Custom styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">

</head>
<body class="hold-transition layout-top-nav">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <!-- Content Wrapper. Contains page content -->
            <div class="content">
                <div class="container">
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <!-- Main content -->
                                    <div class="invoice p-3 mb-3">
                                        <!-- title row -->
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="text-center">
                                                    <img src="{{ url('img/android-icon-96x96.png') }}" alt="Coat of Arms" style="opacity: .8"> <br><br>
                                                    Government of Republic of Zambia<br>
                                                    Ministry of Health <br><br>
                                                    {{ $certificate->target_disease }} Vaccination Certificate <br>
                                                    <small class="text-center">{{ $certificate->created_at }}</small><br><br>
                                                </h4>
                                            </div>
                                            <!-- /.col -->
                                        </div>

                                        <!-- info row -->
                                        <div class="row invoice-info">
                                            <div class="col-sm-4 invoice-col">
                                                <img src="{{ url( $certificate->qr_code_path) }}" alt="Certificate QR Code" style="opacity: .8">
                                            </div>
                                            <!-- /.col -->

                                            <div class="col-sm-8 invoice-col">
                                                <b>Certificate UUID</b> {{ $certificate->certificate_uuid }}<br>
                                                <b>Trusted Vaccine Code:</b> {{ $certificate->trusted_vaccine_code }}<br>
                                                <br>
                                                <b>Last Name:</b> {{ $certificate->client['last_name'] }}<br>
                                                <b>First Name:</b> {{ $certificate->client['first_name'] }}<br>
                                                <b>Other Names:</b> {{ $certificate->client['other_names'] }}<br>
                                                <b>NRC:</b> {{ $certificate->client['NRC'] }}<br>
                                                <b>Passport Number:</b> {{ $certificate->client['passport_number'] }}<br>
                                                <b>Nationality:</b> {{-- $certificate->client['nationality'] --}}<br>
                                                <b>Sex:</b> {{ $certificate->client['sex'] }}<br>
                                                <b>Date of Birth:</b> {{ $certificate->client['date_of_birth']->format('d-M-Y') }}<br>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                        <!-- /.row -->
                                        <br><br>
                                        <!-- Table row -->
                                        <div class="row">
                                            <div class="col-12 table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Vaccine</th>
                                                        <th>Dose Number</th>
                                                        <th>Batch Number</th>
                                                        <th>Vaccinating Organization</th>
                                                        <th>Facility</th>
{{--                                                        <th>Country</th>--}}
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($certificate->vaccinations as $vaccination)
                                                        <tr>
                                                            <td>{{ $vaccination['date']->format('d-M-Y') }}</td>
                                                            <td>{{ $vaccination['vaccine']->product_name }}</td>
                                                            <td>{{ $vaccination['dose_number'] }}</td>
                                                            <td>{{ $vaccination['vaccine_batch_number'] }}</td>
                                                            <td>{{ $vaccination['vaccinating_organization'] }}</td>
                                                            <td>{{ $vaccination['facility']->name }}</td>
{{--                                                            <td>{{ $vaccination['country']->name }}</td>--}}
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                        <!-- /.row -->
                                    </div>
                                    <!-- /.invoice -->
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.container-fluid -->
                    </section>
                    <!-- /.content -->
                </div> <!-- /.container-fluid -->
            </div>
            <!-- /.content-wrapper -->
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->


<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
        integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s"
        crossorigin="anonymous"></script>



</body>
</html>
