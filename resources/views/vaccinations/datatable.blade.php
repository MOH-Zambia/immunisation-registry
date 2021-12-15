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
        #vaccinations-table_wrapper {
            padding: 10px;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Vaccinations</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table" id="vaccinations-table">
                    <thead>
                        <tr>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Date</th>
                            <th>Vaccine</th>
                            <th>Dose Number</th>
                            <th>Vaccine Batch Number</th>
                            <th>Facility</th>
                            <th>Certificate ID</th>
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
            var table = $('#vaccinations-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('vaccinations.datatable') }}",
                columns: [
                    {
                        data: 'last_name',
                        name: 'clients.last_name'
                    },
                    {
                        data: 'first_name',
                        name: 'clients.first_name'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'product_name',
                        name: 'vaccines.product_name'
                    },
                    {
                        data: 'dose_number',
                        name: 'dose_number'
                    },
                    {
                        data: 'vaccine_batch_number',
                        name: 'vaccine_batch_number'
                    },
                    {
                        data: 'name',
                        name: 'facilities.name'
                    },
                    {
                        data: 'certificate_id',
                        name: 'certificate_id'
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

