@extends('layouts.app')

@push('third_party_stylesheets')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.bootstrap4.min.css">
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
        .filter-card {
            margin-bottom: 20px;
        }
        .filter-card .card-header {
            cursor: pointer;
            background-color: #f8f9fa;
        }
        .filter-card .card-header:hover {
            background-color: #e9ecef;
        }
        .column-search input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
            font-size: 12px;
        }
        #certificates-table thead th {
            white-space: nowrap;
        }
        .dt-buttons {
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-certificate"></i> Certificates Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Certificates</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <!-- Advanced Filters -->
        <div class="card filter-card">
            <div class="card-header" data-toggle="collapse" data-target="#filterCollapse">
                <h3 class="card-title">
                    <i class="fas fa-filter"></i> Advanced Filters
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
            </div>
            <div id="filterCollapse" class="collapse">
                <div class="card-body">
                    <form id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Export Status</label>
                                    <select class="form-control" id="trusted_filter" name="trusted_filter">
                                        <option value="">All Certificates</option>
                                        <option value="exported">Exported to Portal</option>
                                        <option value="pending">Pending Export</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="button" class="btn btn-primary" id="applyFilter">
                                            <i class="fas fa-search"></i> Apply Filters
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="resetFilter">
                                            <i class="fas fa-redo"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="total-certificates">...</h3>
                        <p>Total Certificates</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="exported-certificates">...</h3>
                        <p>Exported to Portal</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="pending-certificates">...</h3>
                        <p>Pending Export</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3 id="filtered-count">...</h3>
                        <p>Filtered Results</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-filter"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Certificate Records</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover" id="certificates-table" data-page-length='25'>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client Name</th>
                            <th>Identification</th>
                            <th>Contact</th>
                            <th>Trusted Code</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                        <tr class="column-search">
                            <th></th>
                            <th><input type="text" placeholder="Search name..." /></th>
                            <th><input type="text" placeholder="Search ID..." /></th>
                            <th><input type="text" placeholder="Search contact..." /></th>
                            <th><input type="text" placeholder="Search code..." /></th>
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
@endsection

@push('page_scripts')
    <script type="text/javascript" charset="ut8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="ut8" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="ut8" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap4.min.js"></script>
    <script type="text/javascript" charset="ut8" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="ut8" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
    <script type="text/javascript" charset="ut8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" charset="ut8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" charset="ut8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

    <script type="text/javascript">
        $(function () {
            var table = $('#certificates-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('certificates.datatable') }}",
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.trusted_filter = $('#trusted_filter').val();
                    }
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        },
                        orientation: 'landscape',
                        pageSize: 'A4'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-primary btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    }
                ],
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'client_name',
                        name: 'client_name'
                    },
                    {
                        data: 'identification',
                        name: 'identification'
                    },
                    {
                        data: 'contact_number',
                        name: 'clients.contact_number'
                    },
                    {
                        data: 'trusted_vaccine_code',
                        name: 'certificates.trusted_vaccine_code'
                    },
                    {
                        data: 'trusted_status',
                        name: 'trusted_status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'certificates.created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                drawCallback: function(settings) {
                    updateStatistics(settings.json);
                }
            });

            // Column search
            $('#certificates-table thead .column-search input').on('keyup change', function () {
                var columnIndex = $(this).parent().index();
                table.column(columnIndex).search(this.value).draw();
            });

            // Apply filters
            $('#applyFilter').on('click', function() {
                table.ajax.reload();
            });

            // Reset filters
            $('#resetFilter').on('click', function() {
                $('#start_date').val('');
                $('#end_date').val('');
                $('#trusted_filter').val('');
                table.ajax.reload();
            });

            // Copy UUID to clipboard
            $(document).on('click', '.copy-uuid', function() {
                var uuid = $(this).data('uuid');
                var url = "{{ url('/certificates') }}/" + uuid;

                navigator.clipboard.writeText(url).then(function() {
                    toastr.success('Certificate URL copied to clipboard!');
                }, function() {
                    toastr.error('Failed to copy URL');
                });
            });

            // Update statistics
            function updateStatistics(json) {
                if (json && json.recordsTotal !== undefined) {
                    $('#total-certificates').text(json.recordsTotal.toLocaleString());
                    $('#filtered-count').text(json.recordsFiltered.toLocaleString());
                }
            }

            // Load initial statistics
            $.ajax({
                url: "{{ route('certificates.datatable') }}",
                data: { length: 0 },
                success: function(response) {
                    if (response.recordsTotal) {
                        $('#total-certificates').text(response.recordsTotal.toLocaleString());
                    }
                    // Count exported vs pending
                    $.get("{{ route('admin.system-tools.stats') }}", function(statsResponse) {
                        if (statsResponse.success) {
                            $('#exported-certificates').text(
                                (statsResponse.stats.total_certificates - statsResponse.stats.certificates_without_trusted_code).toLocaleString()
                            );
                            $('#pending-certificates').text(
                                statsResponse.stats.certificates_without_trusted_code.toLocaleString()
                            );
                        }
                    });
                }
            });
        });
    </script>
@endpush

