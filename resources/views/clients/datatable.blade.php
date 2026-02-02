@extends('layouts.app')

@push('third_party_stylesheets')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css">
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
        .stat-card {
            border-left: 4px solid #007bff;
        }
        .stat-card.success {
            border-left-color: #28a745;
        }
        .stat-card.warning {
            border-left-color: #ffc107;
        }
        .stat-card.info {
            border-left-color: #17a2b8;
        }
        .filter-card {
            background-color: #f8f9fa;
        }
        .column-search {
            width: 100%;
            padding: 3px;
            font-size: 12px;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Clients Management</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @include('flash::message')

        <!-- Statistics Cards -->
        <div class="row mb-3">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="total-clients">0</h3>
                        <p>Total Clients</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="with-certificate">0</h3>
                        <p>With Certificate</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="without-certificate">0</h3>
                        <p>Without Certificate</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3 id="fully-vaccinated">0</h3>
                        <p>Fully Vaccinated</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-syringe"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="card filter-card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-filter"></i> Advanced Filters
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form id="filter-form">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date Range</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" id="start_date" name="start_date">
                                    <div class="input-group-append input-group-prepend">
                                        <span class="input-group-text">to</span>
                                    </div>
                                    <input type="date" class="form-control" id="end_date" name="end_date">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Certificate Status</label>
                                <select class="form-control" id="certificate_status" name="certificate_status">
                                    <option value="">All</option>
                                    <option value="has_certificate">Has Certificate</option>
                                    <option value="no_certificate">No Certificate</option>
                                    <option value="exported">Certificate Exported</option>
                                    <option value="not_exported">Not Exported</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Gender</label>
                                <select class="form-control" id="gender" name="gender">
                                    <option value="">All</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Vaccination Status</label>
                                <select class="form-control" id="vaccination_status" name="vaccination_status">
                                    <option value="">All</option>
                                    <option value="fully_vaccinated">Fully Vaccinated</option>
                                    <option value="partially_vaccinated">Partially Vaccinated</option>
                                    <option value="not_vaccinated">Not Vaccinated</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i> Apply Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-secondary btn-sm" id="reset-filters">
                                <i class="fas fa-redo"></i> Reset Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Client List</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-success btn-sm" id="export-excel">
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                    <button type="button" class="btn btn-info btn-sm" id="export-csv">
                        <i class="fas fa-file-csv"></i> CSV
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id="export-pdf">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" id="print-table">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover" id="clients-table" data-page-length='25'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>NRC</th>
                                <th>Passport</th>
                                <th>Card Number</th>
                                <th>Gender</th>
                                <th>Age</th>
                                <th>Phone</th>
                                <th>Certificate Status</th>
                                <th>Vaccination Status</th>
                                <th>Action</th>
                            </tr>
                            <tr>
                                <th><input type="text" class="column-search" placeholder="ID" data-column="0"></th>
                                <th><input type="text" class="column-search" placeholder="Name" data-column="1"></th>
                                <th><input type="text" class="column-search" placeholder="NRC" data-column="2"></th>
                                <th><input type="text" class="column-search" placeholder="Passport" data-column="3"></th>
                                <th><input type="text" class="column-search" placeholder="Card" data-column="4"></th>
                                <th></th>
                                <th></th>
                                <th><input type="text" class="column-search" placeholder="Phone" data-column="7"></th>
                                <th></th>
                                <th></th>
                                <th></th>
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
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

    <script type="text/javascript">
        $(function () {
            var table = $('#clients-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('clients.datatable') }}",
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.certificate_status = $('#certificate_status').val();
                        d.gender = $('#gender').val();
                        d.vaccination_status = $('#vaccination_status').val();
                    }
                },
                columns: [
                    { data: 'id', name: 'clients.id' },
                    { data: 'full_name', name: 'full_name' },
                    { data: 'NRC', name: 'clients.NRC' },
                    { data: 'passport_number', name: 'clients.passport_number' },
                    { data: 'card_number', name: 'clients.card_number' },
                    { data: 'sex', name: 'clients.sex' },
                    { data: 'age', name: 'age', orderable: false },
                    { data: 'contact_number', name: 'clients.contact_number' },
                    { data: 'certificate_status', name: 'certificate_status', orderable: false, searchable: false },
                    { data: 'vaccination_status', name: 'vaccination_status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                order: [[0, 'desc']],
                drawCallback: function(settings) {
                    updateStatistics();
                }
            });

            // Column search
            $('.column-search').on('keyup change', function() {
                var columnIndex = $(this).data('column');
                table.column(columnIndex).search(this.value).draw();
            });

            // Filter form submission
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                table.draw();
            });

            // Reset filters
            $('#reset-filters').on('click', function() {
                $('#filter-form')[0].reset();
                $('.column-search').val('');
                table.search('').columns().search('').draw();
            });

            // Export buttons
            $('#export-excel').on('click', function() {
                window.location.href = "{{ route('clients.datatable') }}?export=excel&" + $('#filter-form').serialize();
            });

            $('#export-csv').on('click', function() {
                window.location.href = "{{ route('clients.datatable') }}?export=csv&" + $('#filter-form').serialize();
            });

            $('#export-pdf').on('click', function() {
                window.location.href = "{{ route('clients.datatable') }}?export=pdf&" + $('#filter-form').serialize();
            });

            $('#print-table').on('click', function() {
                table.button('.buttons-print').trigger();
            });

            // Generate certificate handler
            $(document).on('click', '.generate-certificate', function(e) {
                e.preventDefault();
                var clientId = $(this).data('client-id');
                var button = $(this);

                if (!confirm('Are you sure you want to generate a certificate for this client?')) {
                    return;
                }

                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generating...');

                $.ajax({
                    url: "{{ route('clients.generate-certificate') }}",
                    type: 'POST',
                    data: {
                        client_id: clientId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Certificate generated successfully!\nCertificate Number: ' + response.certificate_number);
                            table.draw(false);
                        } else {
                            alert('Error: ' + response.message);
                            button.prop('disabled', false).html('<i class="fas fa-plus"></i> Generate Certificate');
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = 'An error occurred while generating the certificate.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert('Error: ' + errorMessage);
                        button.prop('disabled', false).html('<i class="fas fa-plus"></i> Generate Certificate');
                    }
                });
            });

            // Update statistics
            function updateStatistics() {
                $.ajax({
                    url: "{{ route('clients.datatable') }}",
                    type: 'GET',
                    data: {
                        stats: true,
                        start_date: $('#start_date').val(),
                        end_date: $('#end_date').val(),
                        certificate_status: $('#certificate_status').val(),
                        gender: $('#gender').val(),
                        vaccination_status: $('#vaccination_status').val()
                    },
                    success: function(response) {
                        if (response.recordsTotal !== undefined) {
                            $('#total-clients').text(response.recordsTotal.toLocaleString());
                        }
                        // Note: Additional statistics would need to be returned from the server
                        // For now, we'll use the filtered count
                        $('#with-certificate').text('N/A');
                        $('#without-certificate').text('N/A');
                        $('#fully-vaccinated').text('N/A');
                    }
                });
            }

            // Initial statistics load
            updateStatistics();
        });
    </script>
@endpush
