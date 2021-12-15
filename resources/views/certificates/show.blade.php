@extends('layouts.app')

@push('page_css')
    <style>
        .vaccination_status{
            background-color: #006430;
            display: block;
            text-align: center;
            color: #ffffff;
            font-size: 20px;
            margin-bottom: 12px;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Certificate Details</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('certificates.index') }}">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </section>

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

                                        <span class="vaccination_status">Fully Vaccinated</span>
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
                                                <th>Facility</th>
{{--                                                <th>Vaccinating Organization</th>--}}
{{--                                                <th>Country</th>--}}
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($certificate->vaccinations as $vaccinations)
                                                <tr>
                                                    <td>{{ $vaccinations['date']->format('d-M-Y') }}</td>
                                                    <td>{{ $vaccinations['vaccine']->product_name }}</td>
                                                    <td>{{ $vaccinations['dose_number'] }}</td>
                                                    <td>{{ $vaccinations['vaccine']->batch_number }}</td>
                                                    <td>{{ $vaccinations['facility']->name }}</td>
{{--                                                    <td>{{ $vaccinations['vaccinating_organization'] }}</td>--}}
{{--                                                    <td>{{ $vaccinations['country']->name }}</td>--}}
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                                <!-- this row will not appear when printing -->
                                <div class="row no-print">
                                    <div class="col-12">
                                        <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>

                                        <button type="button" class="btn btn-success float-right btn-send" style="margin-right: 5px;">
                                            <i class="fas fa-share-square"></i> Send
                                        </button>

                                        <button type="button" class="btn btn-warning float-right" style="margin-right: 5px;">
                                            <i class="fas fa-download"></i> Generate PDF
                                        </button>
                                    </div>
                                </div>
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
@endsection

@push('page_scripts')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".btn-send").click(function(e){

            e.preventDefault();

            $.ajax({
                type:'POST',
                url:"{{ route('ajaxRequest.post') }}",
                data:{id: {{$certificate->id}} },
                success:function(data){
                    alert(data.success);
                }
            });
        });
    </script>
@endpush
