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
        #users-table_wrapper{
            padding: 10px;
        }
        .filter-input{

        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Users</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('users.create') }}">
                        Add New
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table" id="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <th>
                            </th>
                            <th>
                                <input type="text" class="form-control filter-input"
                                       placeholder="Search..." data-column="1" />
                            </th>
                            <th>
                                <input type="text" class="form-control filter-input"
                                       placeholder="Search..." data-column="2" />
                            </th>
                            <th>
                                <input type="text" class="form-control filter-input"
                                       placeholder="Search.." data-column="3" />
                            </th>
                            <th>
                                <input type="text" class="form-control filter-input"
                                       placeholder="Search..." data-column="4" />
                            </th>
                            <th>
                            </th>
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
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.datatable') }}",
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
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
                        data: 'role_id',
                        name: 'role_id'
                    },
                    {
                        data: 'email',
                        name: 'email'
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

