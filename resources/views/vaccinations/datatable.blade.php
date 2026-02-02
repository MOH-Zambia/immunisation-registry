@extends('layouts.app')

@push('third_party_stylesheets')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
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
        .filter-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .filter-section .form-control,
        .filter-section .form-select {
            font-size: 0.9rem;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-syringe"></i> Vaccinations</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button type="button" class="btn btn-success" id="exportBtn">
                        <i class="fas fa-file-excel"></i> Export to CSV
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <!-- Advanced Filters -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter"></i> Advanced Filters</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body filter-section">
                <form id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_from">Vaccination Date From</label>
                                <input type="date" class="form-control" id="date_from" name="date_from">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_to">Vaccination Date To</label>
                                <input type="date" class="form-control" id="date_to" name="date_to">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="facility_id">Facility</label>
                                <select class="form-control" id="facility_id" name="facility_id">
                                    <option value="">All Facilities</option>
                                    @foreach(\\App\\Models\\Facility::orderBy('name')->get() as $facility)
                                        <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="vaccine_id">Vaccine</label>
                                <select class="form-control" id="vaccine_id" name="vaccine_id">
                                    <option value="">All Vaccines</option>
                                    @foreach(\\App\\Models\\Vaccine::orderBy('product_name')->get() as $vaccine)
                                        <option value="{{ $vaccine->id }}">{{ $vaccine->product_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dose_number">Dose Number</label>
                                <select class="form-control" id="dose_number" name="dose_number">
                                    <option value="">All Doses</option>
                                    <option value="1">Dose 1</option>
                                    <option value="2">Dose 2</option>
                                    <option value="3">Dose 3</option>
                                    <option value="Booster">Booster</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="certificate_status">Certificate Status</label>
                                <select class="form-control" id="certificate_status" name="certificate_status">
                                    <option value="">All</option>
                                    <option value="with_certificate">With Certificate</option>
                                    <option value="without_certificate">Without Certificate</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="button" class="btn btn-primary" id="applyFilters">
                                        <i class="fas fa-search"></i> Apply Filters
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="resetFilters">
                                        <i class="fas fa-redo"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Vaccinations Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="vaccinations-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Client Name</th>
                                <th>NRC</th>
                                <th>Vaccination Date</th>
                                <th>Vaccine</th>
                                <th>Dose</th>
                                <th>Batch Number</th>
                                <th>Facility</th>
                                <th>Certificate</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script type="text/javascript" charset="ut8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

    <script type="text/javascript">
        $(function () {
            var table = $('#vaccinations-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('vaccinations.datatable') }}",
                    data: function (d) {
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                        d.facility_id = $('#facility_id').val();
                        d.vaccine_id = $('#vaccine_id').val();
                        d.dose_number = $('#dose_number').val();
                        d.certificate_status = $('#certificate_status').val();
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'client_name',
                        name: 'client_name',
                        orderable: false
                    },
                    {
                        data: 'client_nrc',
                        name: 'client_nrc',
                        orderable: false
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'vaccine_name',
                        name: 'vaccine_name',
                        orderable: false
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
                        data: 'facility_name',
                        name: 'facility_name',
                        orderable: false
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
                ],
                order: [[3, 'desc']], // Order by date descending
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
            });

            // Apply filters
            $('#applyFilters').click(function() {
                table.draw();
            });

            // Reset filters
            $('#resetFilters').click(function() {
                $('#filterForm')[0].reset();
                table.draw();
            });

            // Export functionality
            $('#exportBtn').click(function() {
                var params = new URLSearchParams();
                params.append('date_from', $('#date_from').val());
                params.append('date_to', $('#date_to').val());
                params.append('facility_id', $('#facility_id').val());
                params.append('vaccine_id', $('#vaccine_id').val());
                params.append('dose_number', $('#dose_number').val());
                params.append('certificate_status', $('#certificate_status').val());

                window.location.href = "{{ route('vaccinations.export') }}?" + params.toString();
            });

            // Delete vaccination
            $(document).on('click', '.delete-vaccination', function() {
                var id = $(this).data('id');

                if (confirm('Are you sure you want to delete this vaccination record?')) {
                    $.ajax({
                        url: '/vaccinations/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            table.draw();
                            alert('Vaccination deleted successfully');
                        },
                        error: function(xhr) {
                            alert('Error deleting vaccination: ' + xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endpush

