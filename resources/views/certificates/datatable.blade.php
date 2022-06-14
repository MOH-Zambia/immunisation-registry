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
        .capitalize-text {
            text-transform: capitalize; 
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
                <table class="table" id="certificates-table" data-page-length='25'>
                    <thead>
                        <tr>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Other Names</th>
                            <th>NRC</th>
                            <th>Passport</th>
                            <th>Trusted Vaccine Code</th>
                            <th>Created At</th>
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
                        data: 'last_name',
                        name: 'clients.last_name',
                        searchable: false
                    },
                    {
                        data: 'first_name',
                        name: 'clients.first_name',
                        searchable: false
                    },
                    {
                        data: 'other_names',
                        name: 'clients.other_names',
                        searchable: false
                    },
                    {
                        data: 'NRC',
                        name: 'clients.NRC'
                    },
                    {
                        data: 'passport_number',
                        name: 'clients.passport_number'
                    },
                    {
                        data: 'trusted_vaccine_code',
                        name: 'certificates.trusted_vaccine_code',
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'certificates.created_at',
                        searchable: false
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

