@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>SMS Testing</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">SMS Testing</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="row">
            <!-- SMS Test Form -->
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Send Test SMS</h3>
                    </div>
                    <form id="testSmsForm">
                        @csrf
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Testing Tool:</strong> Use this interface to test SMS gateway connectivity and configuration.
                            </div>

                            <div class="form-group">
                                <label for="gateway">SMS Gateway <span class="text-danger">*</span></label>
                                <select class="form-control" id="gateway" name="gateway" required>
                                    <option value="">Select Gateway</option>
                                    <option value="zamtel">Zamtel Bulk SMS</option>
                                    <option value="kannel">Kannel Gateway</option>
                                </select>
                                <small class="form-text text-muted">Choose which SMS gateway to test</small>
                            </div>

                            <div class="form-group">
                                <label for="phone_number">Phone Number <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control"
                                       id="phone_number"
                                       name="phone_number"
                                       placeholder="260977123456"
                                       required>
                                <small class="form-text text-muted">Enter full phone number with country code (e.g., 260977123456)</small>
                            </div>

                            <div class="form-group">
                                <label for="message">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control"
                                          id="message"
                                          name="message"
                                          rows="4"
                                          placeholder="Enter test message..."
                                          maxlength="160"
                                          required>Test message from Immunisation Registry - {{ now()->format('Y-m-d H:i:s') }}</textarea>
                                <small class="form-text text-muted">
                                    <span id="charCount">0</span>/160 characters
                                </small>
                            </div>

                            <div id="gatewayInfo" class="alert alert-secondary" style="display: none;">
                                <strong>Gateway Configuration:</strong>
                                <div id="gatewayDetails"></div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="sendBtn">
                                <i class="fas fa-paper-plane"></i> Send Test SMS
                            </button>
                            <button type="button" class="btn btn-secondary" id="clearBtn">
                                <i class="fas fa-eraser"></i> Clear
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Panel -->
            <div class="col-md-6">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Test Results</h3>
                    </div>
                    <div class="card-body">
                        <div id="resultPanel" class="text-center text-muted" style="padding: 50px 20px;">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <p>Results will appear here after sending a test SMS</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Tests Log -->
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Test History</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush" id="testHistory">
                            <li class="list-group-item text-muted text-center">No tests yet</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration Info -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card card-warning collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">Gateway Configuration Status</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><i class="fas fa-satellite-dish"></i> Zamtel Bulk SMS</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <th>API Key:</th>
                                        <td>
                                            @if(env('ZAMTEL_BULK_SMS_API_KEY'))
                                                <span class="badge badge-success">Configured</span>
                                            @else
                                                <span class="badge badge-danger">Not Set</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Sender ID:</th>
                                        <td>
                                            @if(env('ZAMTEL_SENDER_ID'))
                                                <code>{{ env('ZAMTEL_SENDER_ID') }}</code>
                                            @else
                                                <span class="badge badge-danger">Not Set</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Endpoint:</th>
                                        <td><small>https://bulksms.zamtel.co.zm/api/v2.1/</small></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5><i class="fas fa-server"></i> Kannel Gateway</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <th>Host:</th>
                                        <td>
                                            @if(env('KANNEL_HOST'))
                                                <code>{{ env('KANNEL_HOST') }}</code>
                                            @else
                                                <span class="badge badge-danger">Not Set</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Port:</th>
                                        <td>
                                            @if(env('KANNEL_PORT'))
                                                <code>{{ env('KANNEL_PORT') }}</code>
                                            @else
                                                <span class="badge badge-danger">Not Set</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Username:</th>
                                        <td>
                                            @if(env('KANNEL_USERNAME'))
                                                <span class="badge badge-success">Configured</span>
                                            @else
                                                <span class="badge badge-danger">Not Set</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Sender:</th>
                                        <td>
                                            @if(env('KANNEL_SENDER'))
                                                <code>{{ env('KANNEL_SENDER') }}</code>
                                            @else
                                                <span class="badge badge-danger">Not Set</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
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
    let testCounter = 0;

    // Character counter
    $('#message').on('input', function() {
        const length = $(this).val().length;
        $('#charCount').text(length);

        if (length > 140) {
            $('#charCount').addClass('text-warning');
        } else {
            $('#charCount').removeClass('text-warning');
        }
    });

    // Gateway info
    $('#gateway').on('change', function() {
        const gateway = $(this).val();
        if (gateway) {
            $('#gatewayInfo').show();
            if (gateway === 'zamtel') {
                $('#gatewayDetails').html(`
                    <ul class="mb-0">
                        <li>Endpoint: https://bulksms.zamtel.co.zm/api/v2.1/</li>
                        <li>Authentication: API Key</li>
                        <li>Method: GET (URL parameters)</li>
                    </ul>
                `);
            } else {
                $('#gatewayDetails').html(`
                    <ul class="mb-0">
                        <li>Protocol: HTTP/HTTPS</li>
                        <li>Authentication: Username/Password</li>
                        <li>Method: GET (Kannel CGI)</li>
                    </ul>
                `);
            }
        } else {
            $('#gatewayInfo').hide();
        }
    });

    // Clear form
    $('#clearBtn').on('click', function() {
        $('#testSmsForm')[0].reset();
        $('#charCount').text('0');
        $('#gatewayInfo').hide();
    });

    // Form submission
    $('#testSmsForm').on('submit', function(e) {
        e.preventDefault();

        const $sendBtn = $('#sendBtn');
        const originalBtnText = $sendBtn.html();

        // Disable button and show loading
        $sendBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');

        // Show loading in result panel
        $('#resultPanel').html(`
            <div class="text-center">
                <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                <p>Sending test SMS...</p>
            </div>
        `);

        $.ajax({
            url: "{{ route('admin.test-sms.send') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                testCounter++;

                // Display success result
                $('#resultPanel').html(`
                    <div class="alert alert-success">
                        <h4><i class="fas fa-check-circle"></i> Success!</h4>
                        <p>${response.message}</p>
                    </div>
                    <table class="table table-sm">
                        <tr>
                            <th>Gateway:</th>
                            <td><span class="badge badge-info">${response.details.gateway.toUpperCase()}</span></td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td><code>${response.details.phone}</code></td>
                        </tr>
                        <tr>
                            <th>HTTP Code:</th>
                            <td><span class="badge badge-success">${response.details.http_code}</span></td>
                        </tr>
                        <tr>
                            <th>Execution Time:</th>
                            <td>${response.details.execution_time_ms} ms</td>
                        </tr>
                        <tr>
                            <th>Response:</th>
                            <td><pre class="small mb-0" style="max-height: 100px; overflow-y: auto;">${response.details.response_preview}</pre></td>
                        </tr>
                    </table>
                `);

                // Add to history
                addToHistory('success', response.details.gateway, response.details.phone, response.details.http_code);
            },
            error: function(xhr) {
                const error = xhr.responseJSON;

                // Display error result
                $('#resultPanel').html(`
                    <div class="alert alert-danger">
                        <h4><i class="fas fa-times-circle"></i> Failed!</h4>
                        <p>${error.message || 'Unknown error occurred'}</p>
                    </div>
                    ${error.error ? `<div class="alert alert-warning"><strong>Error Details:</strong> ${error.error}</div>` : ''}
                    ${error.details ? `
                        <table class="table table-sm">
                            <tr>
                                <th>Gateway:</th>
                                <td><span class="badge badge-secondary">${error.details.gateway.toUpperCase()}</span></td>
                            </tr>
                            ${error.details.http_code ? `
                            <tr>
                                <th>HTTP Code:</th>
                                <td><span class="badge badge-danger">${error.details.http_code}</span></td>
                            </tr>
                            ` : ''}
                            ${error.details.response_preview ? `
                            <tr>
                                <th>Response:</th>
                                <td><pre class="small mb-0" style="max-height: 100px; overflow-y: auto;">${error.details.response_preview}</pre></td>
                            </tr>
                            ` : ''}
                        </table>
                    ` : ''}
                `);

                // Add to history
                addToHistory('error', error.details?.gateway || 'unknown', 'N/A', error.details?.http_code || 'N/A');
            },
            complete: function() {
                // Re-enable button
                $sendBtn.prop('disabled', false).html(originalBtnText);
            }
        });
    });

    function addToHistory(status, gateway, phone, httpCode) {
        const timestamp = new Date().toLocaleTimeString();
        const statusBadge = status === 'success'
            ? '<span class="badge badge-success">Success</span>'
            : '<span class="badge badge-danger">Failed</span>';

        const historyItem = `
            <li class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${statusBadge}</strong>
                        <span class="badge badge-info ml-2">${gateway.toUpperCase()}</span>
                        <br>
                        <small class="text-muted">${timestamp} | Phone: ${phone} | HTTP: ${httpCode}</small>
                    </div>
                </div>
            </li>
        `;

        if ($('#testHistory').find('.text-muted').length) {
            $('#testHistory').html(historyItem);
        } else {
            $('#testHistory').prepend(historyItem);
        }

        // Keep only last 5 items
        if ($('#testHistory li').length > 5) {
            $('#testHistory li:last').remove();
        }
    }

    // Trigger character count on load
    $('#message').trigger('input');
});
</script>
@endpush
