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
        #clients-table_wrapper {
            padding: 10px;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Clients</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table" id="clients-table">
                    <thead>
                        <tr>
                            <th>Source ID</th>
                            <th>Card Number</th>
                            <th>NRC</th>
                            <th>Passport Number</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Other Names</th>
                            <th>Sex</th>
                            <th>Phone Number</th>
                            <th>Email Address</th>
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
            var table = $('#clients-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('clients.datatable') }}",
                columns: [
                    {
                        data: 'source_id',
                        name: 'source_id'
                    },
                    {
                        data: 'card_number',
                        name: 'card_number'
                    },
                    {
                        data: 'NRC',
                        name: 'NRC'
                    },
                    {
                        data: 'passport_number',
                        name: 'passport_number'
                    },
                    {
                        data: 'last_name',
                        name: 'last_name'
                    },
                    {
                        data: 'first_name',
                        name: 'first_name'
                    },
                    {
                        data: 'other_names',
                        name: 'other_names'
                    },
                    {
                        data: 'sex',
                        name: 'sex'
                    },
                    {
                        data: 'contact_number',
                        name: 'contact_number'
                    },
                    {
                        data: 'contact_email_address',
                        name: 'contact_email_address'
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

