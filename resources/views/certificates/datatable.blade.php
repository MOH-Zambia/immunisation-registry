@extends('layouts.app')

@push('third_party_stylesheets')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
@endpush

@push('page_css')
    <style type="text/css">
        .dataTables_filter {
            width: 50%;
            float: right;
            text-align: right;
        }
        #certificates-table_wrapper {
            padding: 10px;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Certificates</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table" id="certificates-table">
                    <thead>
                        <tr>
                            <th>Certificate UUID</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Other Names</th>
                            <th>Dose 1 Date</th>
                            <th>Dose 2 Date</th>
                            <th>Booster Dose Date</th>
                            <th>Trusted Vaccine Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@push('page_scripts')
    <script type="text/javascript" charset="ut8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

    <script type="text/javascript">
        $(function () {
            var table = $('#certificates-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('certificates.datatable') }}",
                columns: [
                    {
                        data: 'certificate_uuid',
                        name: 'certificate_uuid'
                    },
                    {
                        data: 'last_name',
                        name: 'clients.last_name'
                    },
                    {
                        data: 'first_name',
                        name: 'clients.first_name'
                    },
                    {
                        data: 'other_names',
                        name: 'clients.other_names'
                    },
                    {
                        data: 'dose_1_date',
                        name: 'dose_1_date'
                    },
                    {
                        data: 'dose_2_date',
                        name: 'dose_2_date'
                    },
                    {
                        data: 'booster_dose_date',
                        name: 'booster_dose_date'
                    },
                    {
                        data: 'trusted_vaccine_code',
                        name: 'trusted_vaccine_code'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('.filter-input').keyup(function(){
                table.column($(this).data('column'))
                .search($(this).val())
                .draw();
            });
        });
    </script>
@endpush

