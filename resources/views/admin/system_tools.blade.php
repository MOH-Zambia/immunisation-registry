@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>System Tools</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">System Tools</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <!-- Statistics Row -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="stat-vaccinations-without-cert">{{ number_format($stats['vaccinations_without_certificates']) }}</h3>
                        <p>Vaccinations Without Certificates</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-medical"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="stat-certs-without-code">{{ number_format($stats['certificates_without_trusted_code']) }}</h3>
                        <p>Certificates Not Exported</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="stat-total-certs">{{ number_format($stats['total_certificates']) }}</h3>
                        <p>Total Certificates</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3 id="stat-total-vaccinations">{{ number_format($stats['total_vaccinations']) }}</h3>
                        <p>Total Vaccinations</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-syringe"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Certificate Generation Tool -->
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-certificate"></i> Generate Vaccination Certificates
                        </h3>
                    </div>
                    <div class="card-body">
                        <p>Generate certificates for all vaccinations that don't have certificates yet.</p>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Current Status:</strong> {{ number_format($stats['vaccinations_without_certificates']) }} vaccinations without certificates
                        </div>
                        <button type="button" class="btn btn-primary btn-block" id="generateCertificatesBtn">
                            <i class="fas fa-play"></i> Generate Certificates
                        </button>
                        <div id="generateCertificatesOutput" class="mt-3" style="display: none;">
                            <div class="alert alert-secondary">
                                <h6><strong>Output:</strong></h6>
                                <pre id="generateCertificatesLog" style="background: #f4f4f4; padding: 10px; max-height: 300px; overflow-y: auto; font-size: 11px;"></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DHIS2 Import Tool -->
            <div class="col-md-6">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-download"></i> Import DHIS2 Data
                        </h3>
                    </div>
                    <div class="card-body">
                        <p>Import vaccination data from DHIS2 COVAX instance.</p>
                        <form id="dhis2ImportForm">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="date" class="form-control" id="dhis2StartDate" required max="{{ date('Y-m-d') }}">
                            </div>
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="date" class="form-control" id="dhis2EndDate" required max="{{ date('Y-m-d') }}">
                            </div>
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-play"></i> Import Data
                            </button>
                        </form>
                        <div id="dhis2ImportOutput" class="mt-3" style="display: none;">
                            <div class="alert alert-secondary">
                                <h6><strong>Output:</strong></h6>
                                <pre id="dhis2ImportLog" style="background: #f4f4f4; padding: 10px; max-height: 300px; overflow-y: auto; font-size: 11px;"></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Export to Trusted Vaccine Portal -->
            <div class="col-md-6">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cloud-upload-alt"></i> Export to Trusted Vaccine Portal
                        </h3>
                    </div>
                    <div class="card-body">
                        <p>Export certificates to the Trusted Vaccine Portal (PanaBIOS).</p>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Pending Export:</strong> {{ number_format($stats['certificates_without_trusted_code']) }} certificates
                        </div>
                        <button type="button" class="btn btn-warning btn-block" id="exportTrustedPortalBtn">
                            <i class="fas fa-play"></i> Export Certificates
                        </button>
                        <div id="exportTrustedPortalOutput" class="mt-3" style="display: none;">
                            <div class="alert alert-secondary">
                                <h6><strong>Output:</strong></h6>
                                <pre id="exportTrustedPortalLog" style="background: #f4f4f4; padding: 10px; max-height: 300px; overflow-y: auto; font-size: 11px;"></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Maintenance Tools -->
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tools"></i> System Maintenance
                        </h3>
                    </div>
                    <div class="card-body">
                        <p>Perform system maintenance tasks to optimize performance.</p>

                        <div class="btn-group-vertical btn-block">
                            <button type="button" class="btn btn-outline-info" id="clearCacheBtn">
                                <i class="fas fa-broom"></i> Clear All Caches
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="optimizeAppBtn">
                                <i class="fas fa-tachometer-alt"></i> Optimize Application
                            </button>
                            <button type="button" class="btn btn-outline-success" id="refreshStatsBtn">
                                <i class="fas fa-sync"></i> Refresh Statistics
                            </button>
                        </div>

                        <div id="maintenanceOutput" class="mt-3" style="display: none;">
                            <div class="alert alert-secondary">
                                <h6><strong>Result:</strong></h6>
                                <p id="maintenanceMessage"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
<script>
$(document).ready(function() {
    // Generate Certificates
    $('#generateCertificatesBtn').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generating...');
        $('#generateCertificatesOutput').hide();

        $.ajax({
            url: "{{ route('admin.system-tools.generate-certificates') }}",
            method: 'POST',
            data: { _token: "{{ csrf_token() }}" },
            success: function(response) {
                if (response.success) {
                    $('#generateCertificatesLog').text(response.output);
                    $('#generateCertificatesOutput').show();
                    toastr.success(response.message + ' (Duration: ' + response.execution_time + ')');
                    refreshStats();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'An error occurred';
                toastr.error(message);
                if (xhr.responseJSON?.output) {
                    $('#generateCertificatesLog').text(xhr.responseJSON.output);
                    $('#generateCertificatesOutput').show();
                }
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-play"></i> Generate Certificates');
            }
        });
    });

    // DHIS2 Import
    $('#dhis2ImportForm').on('submit', function(e) {
        e.preventDefault();

        const btn = $(this).find('button[type="submit"]');
        const startDate = $('#dhis2StartDate').val();
        const endDate = $('#dhis2EndDate').val();

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Importing...');
        $('#dhis2ImportOutput').hide();

        $.ajax({
            url: "{{ route('admin.system-tools.import-dhis2') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                start_date: startDate,
                end_date: endDate
            },
            success: function(response) {
                if (response.success) {
                    $('#dhis2ImportLog').text(response.output);
                    $('#dhis2ImportOutput').show();
                    toastr.success(response.message + ' (Duration: ' + response.execution_time + ')');
                    refreshStats();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'An error occurred';
                toastr.error(message);
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-play"></i> Import Data');
            }
        });
    });

    // Export to Trusted Portal
    $('#exportTrustedPortalBtn').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Exporting...');
        $('#exportTrustedPortalOutput').hide();

        $.ajax({
            url: "{{ route('admin.system-tools.export-trusted-portal') }}",
            method: 'POST',
            data: { _token: "{{ csrf_token() }}" },
            success: function(response) {
                if (response.success) {
                    $('#exportTrustedPortalLog').text(response.output);
                    $('#exportTrustedPortalOutput').show();
                    toastr.success(response.message + ' (Duration: ' + response.execution_time + ')');
                    refreshStats();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'An error occurred';
                toastr.error(message);
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-play"></i> Export Certificates');
            }
        });
    });

    // Clear Cache
    $('#clearCacheBtn').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Clearing...');

        $.ajax({
            url: "{{ route('admin.system-tools.clear-cache') }}",
            method: 'POST',
            data: { _token: "{{ csrf_token() }}" },
            success: function(response) {
                if (response.success) {
                    $('#maintenanceMessage').text(response.message);
                    $('#maintenanceOutput').show();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'An error occurred');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-broom"></i> Clear All Caches');
            }
        });
    });

    // Optimize Application
    $('#optimizeAppBtn').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Optimizing...');

        $.ajax({
            url: "{{ route('admin.system-tools.optimize') }}",
            method: 'POST',
            data: { _token: "{{ csrf_token() }}" },
            success: function(response) {
                if (response.success) {
                    $('#maintenanceMessage').text(response.message);
                    $('#maintenanceOutput').show();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'An error occurred');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-tachometer-alt"></i> Optimize Application');
            }
        });
    });

    // Refresh Statistics
    $('#refreshStatsBtn').on('click', function() {
        refreshStats();
    });

    function refreshStats() {
        const btn = $('#refreshStatsBtn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Refreshing...');

        $.ajax({
            url: "{{ route('admin.system-tools.stats') }}",
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#stat-vaccinations-without-cert').text(response.stats.vaccinations_without_certificates.toLocaleString());
                    $('#stat-certs-without-code').text(response.stats.certificates_without_trusted_code.toLocaleString());
                    $('#stat-total-certs').text(response.stats.total_certificates.toLocaleString());
                    $('#stat-total-vaccinations').text(response.stats.total_vaccinations.toLocaleString());
                    toastr.success('Statistics refreshed');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'An error occurred');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-sync"></i> Refresh Statistics');
            }
        });
    }

    // Set default dates for DHIS2 import (last 7 days)
    const today = new Date();
    const lastWeek = new Date(today);
    lastWeek.setDate(lastWeek.getDate() - 7);

    $('#dhis2EndDate').val(today.toISOString().split('T')[0]);
    $('#dhis2StartDate').val(lastWeek.toISOString().split('T')[0]);
});
</script>
@endpush
