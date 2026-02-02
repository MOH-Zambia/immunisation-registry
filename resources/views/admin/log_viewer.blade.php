@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Log Viewer</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Log Viewer</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="row">
            <!-- Log Files List -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Log Files</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-sm btn-danger" id="clearAllBtn" title="Clear old logs">
                                <i class="fas fa-trash"></i> Clear Old Logs
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if(count($logFiles) > 0)
                            <div class="list-group list-group-flush">
                                @foreach($logFiles as $log)
                                    <a href="#" class="list-group-item list-group-item-action log-file" data-file="{{ $log['name'] }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-file-alt text-primary"></i>
                                                <strong>{{ $log['name'] }}</strong>
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-sm btn-info download-log" data-file="{{ $log['name'] }}" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                                @if($log['name'] !== 'laravel-' . date('Y-m-d') . '.log')
                                                    <button class="btn btn-sm btn-danger delete-log" data-file="{{ $log['name'] }}" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            <i class="far fa-clock"></i> {{ $log['modified'] }} |
                                            <i class="far fa-file"></i> {{ $log['size'] }}
                                        </small>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted p-4">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>No log files found</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Display Options</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Lines to display:</label>
                            <select class="form-control" id="linesCount">
                                <option value="50">50 lines</option>
                                <option value="100" selected>100 lines</option>
                                <option value="200">200 lines</option>
                                <option value="500">500 lines</option>
                                <option value="1000">1000 lines</option>
                            </select>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="autoRefresh">
                            <label class="form-check-label" for="autoRefresh">
                                Auto-refresh (30s)
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Log Content Viewer -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title" id="currentLogTitle">Select a log file</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-sm btn-primary" id="refreshBtn" disabled>
                                <i class="fas fa-sync"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="logContent" class="text-center text-muted" style="min-height: 400px;">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <p>Select a log file from the list to view its contents</p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="logInfo" class="text-muted small" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
<script>
$(document).ready(function() {
    let currentFile = null;
    let autoRefreshInterval = null;

    // View log file
    $('.log-file').on('click', function(e) {
        e.preventDefault();
        const fileName = $(this).data('file');
        loadLogFile(fileName);
    });

    // Refresh button
    $('#refreshBtn').on('click', function() {
        if (currentFile) {
            loadLogFile(currentFile);
        }
    });

    // Download log
    $(document).on('click', '.download-log', function(e) {
        e.stopPropagation();
        const fileName = $(this).data('file');
        window.location.href = "{{ route('admin.logs.download') }}?file=" + fileName;
    });

    // Delete log
    $(document).on('click', '.delete-log', function(e) {
        e.stopPropagation();
        const fileName = $(this).data('file');

        if (confirm('Are you sure you want to delete ' + fileName + '?')) {
            deleteLogFile(fileName);
        }
    });

    // Clear all logs
    $('#clearAllBtn').on('click', function() {
        if (confirm('Are you sure you want to delete all old log files? Today\'s log will be kept.')) {
            clearAllLogs();
        }
    });

    // Auto-refresh toggle
    $('#autoRefresh').on('change', function() {
        if ($(this).is(':checked')) {
            startAutoRefresh();
        } else {
            stopAutoRefresh();
        }
    });

    // Lines count change
    $('#linesCount').on('change', function() {
        if (currentFile) {
            loadLogFile(currentFile);
        }
    });

    function loadLogFile(fileName) {
        currentFile = fileName;
        const lines = $('#linesCount').val();

        $('#logContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading...</p></div>');
        $('#currentLogTitle').text('Loading ' + fileName + '...');
        $('#refreshBtn').prop('disabled', true);

        $.ajax({
            url: "{{ route('admin.logs.view') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                file: fileName,
                lines: lines
            },
            success: function(response) {
                $('#currentLogTitle').html('<i class="fas fa-file-alt"></i> ' + response.file);
                $('#refreshBtn').prop('disabled', false);

                const content = formatLogContent(response.content);
                $('#logContent').html('<pre style="background: #1e1e1e; color: #d4d4d4; padding: 15px; border-radius: 5px; max-height: 600px; overflow-y: auto; font-size: 12px; line-height: 1.4;">' + content + '</pre>');

                $('#logInfo').html(
                    '<strong>Total lines:</strong> ' + response.total_lines + ' | ' +
                    '<strong>Showing:</strong> last ' + response.showing_lines + ' lines'
                ).show();

                // Highlight active log file
                $('.log-file').removeClass('active');
                $('.log-file[data-file="' + fileName + '"]').addClass('active');
            },
            error: function(xhr) {
                $('#logContent').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> ' + (xhr.responseJSON?.message || 'Error loading log file') + '</div>');
                $('#currentLogTitle').text('Error');
            }
        });
    }

    function deleteLogFile(fileName) {
        $.ajax({
            url: "{{ route('admin.logs.delete') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                file: fileName
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.message || 'Error deleting log file');
            }
        });
    }

    function clearAllLogs() {
        $.ajax({
            url: "{{ route('admin.logs.clear') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                alert(response.message);
                if (response.success) {
                    location.reload();
                }
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.message || 'Error clearing logs');
            }
        });
    }

    function formatLogContent(content) {
        // Escape HTML
        content = $('<div>').text(content).html();

        // Highlight log levels
        content = content.replace(/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/g, '<span style="color: #569cd6;">[$1]</span>');
        content = content.replace(/\.ERROR:/g, '<span style="color: #f44336; font-weight: bold;">.ERROR:</span>');
        content = content.replace(/\.WARNING:/g, '<span style="color: #ff9800; font-weight: bold;">.WARNING:</span>');
        content = content.replace(/\.INFO:/g, '<span style="color: #4caf50; font-weight: bold;">.INFO:</span>');
        content = content.replace(/\.DEBUG:/g, '<span style="color: #9c27b0; font-weight: bold;">.DEBUG:</span>');

        return content;
    }

    function startAutoRefresh() {
        autoRefreshInterval = setInterval(function() {
            if (currentFile) {
                loadLogFile(currentFile);
            }
        }, 30000); // 30 seconds
    }

    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
            autoRefreshInterval = null;
        }
    }

    // Load today's log by default if available
    @if(count($logFiles) > 0)
        loadLogFile("{{ $logFiles[0]['name'] }}");
    @endif
});
</script>
@endpush
