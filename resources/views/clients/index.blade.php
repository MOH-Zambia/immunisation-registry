@extends('layouts.app')

@push('third_party_stylesheets')
    @include('layouts.datatables_css')
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Clients</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('clients.create') }}">
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
                @include('clients.table')

                <div class="card-footer clearfix float-right">
                    <div class="float-right">
                        @include('adminlte-templates::common.paginate', ['records' => $clients])
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('third_party_scripts')
    @include('layouts.datatables_js')
@endsection

<!-- Page specific script -->
@push('page_scripts')
    <script>
        $(function () {
            $("#clients-table").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#clients-table_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
