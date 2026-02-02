<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | Verify Vaccination Certificate</title>

    <!-- Bootstrap 4 CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css'>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .verify-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .header-section img {
            max-height: 80px;
            margin-bottom: 15px;
        }
        .content-section {
            padding: 40px;
        }
        .tab-content {
            padding: 30px 0;
        }
        .nav-tabs {
            border-bottom: 2px solid #667eea;
        }
        .nav-tabs .nav-link {
            border: none;
            color: #666;
            font-weight: 500;
            padding: 12px 30px;
        }
        .nav-tabs .nav-link.active {
            color: #667eea;
            border-bottom: 3px solid #667eea;
        }
        #reader {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            border: 2px solid #667eea;
            border-radius: 10px;
        }
        .verification-result {
            display: none;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .verification-result.success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .verification-result.error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .certificate-details {
            margin-top: 20px;
        }
        .certificate-details .detail-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }
        .certificate-details .detail-item:last-child {
            border-bottom: none;
        }
        .certificate-details .label {
            font-weight: 600;
            color: #666;
        }
        .certificate-details .value {
            color: #333;
        }
        .btn-verify {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 40px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 25px;
            transition: all 0.3s;
        }
        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .scanner-status {
            text-align: center;
            padding: 20px;
            color: #666;
        }
        .instruction-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .status-valid {
            background: #d4edda;
            color: #155724;
        }
        .status-invalid {
            background: #f8d7da;
            color: #721c24;
        }
        .status-expired {
            background: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="header-section">
            <img src="{{ url('img/android-icon-96x96.png') }}" alt="Coat of Arms">
            <h2>Verify Vaccination Certificate</h2>
            <p>Verify the authenticity of COVID-19 vaccination certificates</p>
        </div>

        <div class="content-section">
            <ul class="nav nav-tabs" id="verifyTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="qr-tab" data-toggle="tab" href="#qr-scan" role="tab">
                        <i class="fas fa-qrcode"></i> Scan QR Code
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="manual-tab" data-toggle="tab" href="#manual-input" role="tab">
                        <i class="fas fa-keyboard"></i> Manual Input
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="verifyTabsContent">
                <!-- QR Code Scanner Tab -->
                <div class="tab-pane fade show active" id="qr-scan" role="tabpanel">
                    <div class="instruction-box">
                        <h6><i class="fas fa-info-circle"></i> How to scan:</h6>
                        <ol style="margin: 10px 0 0 0; padding-left: 20px;">
                            <li>Allow camera access when prompted</li>
                            <li>Position the QR code within the camera frame</li>
                            <li>Wait for automatic detection</li>
                            <li>Verification results will appear below</li>
                        </ol>
                    </div>

                    <div id="reader"></div>
                    <div class="scanner-status" id="scanner-status">
                        <i class="fas fa-camera fa-2x text-muted mb-2"></i>
                        <p>Click "Start Scanner" to begin scanning QR codes</p>
                        <button class="btn btn-verify" id="start-scanner-btn">
                            <i class="fas fa-camera"></i> Start Scanner
                        </button>
                    </div>
                </div>

                <!-- Manual Input Tab -->
                <div class="tab-pane fade" id="manual-input" role="tabpanel">
                    <div class="instruction-box">
                        <h6><i class="fas fa-info-circle"></i> Manual Verification:</h6>
                        <p style="margin: 10px 0 0 0;">Enter the Certificate UUID found on the vaccination certificate. The UUID is a unique identifier usually displayed below the QR code.</p>
                    </div>

                    <form id="manual-verify-form">
                        @csrf
                        <div class="form-group">
                            <label for="certificate_uuid"><strong>Certificate UUID:</strong></label>
                            <input type="text"
                                   class="form-control form-control-lg"
                                   id="certificate_uuid"
                                   name="certificate_uuid"
                                   placeholder="e.g., 550e8400-e29b-41d4-a716-446655440000"
                                   required>
                            <small class="form-text text-muted">
                                The UUID is typically in the format: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
                            </small>
                        </div>
                        <button type="submit" class="btn btn-verify btn-lg btn-block">
                            <i class="fas fa-check-circle"></i> Verify Certificate
                        </button>
                    </form>
                </div>
            </div>

            <!-- Verification Result -->
            <div id="verification-result" class="verification-result">
                <div id="result-content"></div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <!-- HTML5 QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        $(document).ready(function() {
            let html5QrCode = null;
            let isScanning = false;

            // Setup CSRF token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Start QR Scanner
            $('#start-scanner-btn').click(function() {
                if (isScanning) {
                    stopScanner();
                } else {
                    startScanner();
                }
            });

            function startScanner() {
                html5QrCode = new Html5Qrcode("reader");

                $('#scanner-status').html('<p><i class="fas fa-spinner fa-spin"></i> Starting camera...</p>');

                html5QrCode.start(
                    { facingMode: "environment" },
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    onScanSuccess,
                    onScanError
                ).then(() => {
                    isScanning = true;
                    $('#start-scanner-btn').html('<i class="fas fa-stop"></i> Stop Scanner').removeClass('btn-verify').addClass('btn-danger');
                    $('#scanner-status').html('<p class="text-success"><i class="fas fa-camera"></i> Camera active - Point at QR code</p>');
                }).catch(err => {
                    console.error('Failed to start scanner:', err);
                    $('#scanner-status').html(`
                        <p class="text-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            Failed to start camera: ${err}
                        </p>
                        <p class="text-muted">Please ensure camera permissions are granted</p>
                    `);
                });
            }

            function stopScanner() {
                if (html5QrCode) {
                    html5QrCode.stop().then(() => {
                        isScanning = false;
                        $('#start-scanner-btn').html('<i class="fas fa-camera"></i> Start Scanner').removeClass('btn-danger').addClass('btn-verify');
                        $('#scanner-status').html(`
                            <i class="fas fa-camera fa-2x text-muted mb-2"></i>
                            <p>Scanner stopped. Click "Start Scanner" to scan again.</p>
                        `);
                    }).catch(err => {
                        console.error('Failed to stop scanner:', err);
                    });
                }
            }

            function onScanSuccess(decodedText, decodedResult) {
                console.log(`QR Code detected: ${decodedText}`);
                stopScanner();
                verifyCertificate(decodedText);
            }

            function onScanError(errorMessage) {
                // Ignore scan errors (too verbose)
            }

            // Manual form submission
            $('#manual-verify-form').submit(function(e) {
                e.preventDefault();
                const uuid = $('#certificate_uuid').val().trim();
                if (uuid) {
                    verifyCertificate(uuid);
                }
            });

            // Verify certificate function
            function verifyCertificate(uuid) {
                showLoading();

                $.ajax({
                    url: "{{ route('certificates.verify') }}",
                    type: "POST",
                    data: { certificate_uuid: uuid },
                    success: function(response) {
                        showSuccess(response);
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON && xhr.responseJSON.message
                            ? xhr.responseJSON.message
                            : 'Certificate verification failed';
                        showError(errorMsg);
                    }
                });
            }

            function showLoading() {
                $('#verification-result').show().removeClass('success error');
                $('#result-content').html(`
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin fa-3x mb-3"></i>
                        <h5>Verifying certificate...</h5>
                        <p>Please wait while we validate the certificate details.</p>
                    </div>
                `);
            }

            function showSuccess(data) {
                const cert = data.data;
                const statusClass = cert.certificate_status === 'Valid' ? 'status-valid' :
                                  cert.certificate_status === 'Expired' ? 'status-expired' : 'status-invalid';

                $('#verification-result').removeClass('error').addClass('success').show();
                $('#result-content').html(`
                    <div class="text-center mb-4">
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h4>Certificate Verified</h4>
                        <p>This certificate has been verified in our system.</p>
                    </div>

                    <div class="certificate-details">
                        <h5 class="mb-3"><i class="fas fa-id-card"></i> Certificate Details</h5>

                        <div class="detail-item">
                            <span class="label">Certificate UUID:</span>
                            <span class="value"><code>${cert.certificate_uuid}</code></span>
                        </div>

                        <div class="detail-item">
                            <span class="label">Status:</span>
                            <span class="value"><span class="status-badge ${statusClass}">${cert.certificate_status}</span></span>
                        </div>

                        <div class="detail-item">
                            <span class="label">Holder Name:</span>
                            <span class="value">${cert.client.first_name} ${cert.client.last_name}</span>
                        </div>

                        <div class="detail-item">
                            <span class="label">Date of Birth:</span>
                            <span class="value">${formatDate(cert.client.date_of_birth)}</span>
                        </div>

                        <div class="detail-item">
                            <span class="label">Target Disease:</span>
                            <span class="value">${cert.target_disease}</span>
                        </div>

                        ${cert.certificate_expiration_date ? `
                        <div class="detail-item">
                            <span class="label">Expiration Date:</span>
                            <span class="value">${formatDate(cert.certificate_expiration_date)}</span>
                        </div>
                        ` : ''}

                        <div class="detail-item">
                            <span class="label">Issued Date:</span>
                            <span class="value">${formatDate(cert.created_at)}</span>
                        </div>

                        ${cert.trusted_vaccine_code ? `
                        <div class="detail-item">
                            <span class="label">Trusted Vaccine Code:</span>
                            <span class="value"><code>${cert.trusted_vaccine_code}</code></span>
                        </div>
                        ` : ''}
                    </div>

                    <div class="text-center mt-4">
                        <a href="${cert.certificate_url}" target="_blank" class="btn btn-primary">
                            <i class="fas fa-file-pdf"></i> View Full Certificate
                        </a>
                    </div>
                `);
            }

            function showError(message) {
                $('#verification-result').removeClass('success').addClass('error').show();
                $('#result-content').html(`
                    <div class="text-center mb-3">
                        <i class="fas fa-times-circle fa-4x text-danger mb-3"></i>
                        <h4>Verification Failed</h4>
                        <p class="lead">${message}</p>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> What to do:</h6>
                        <ul>
                            <li>Verify you have entered the correct UUID</li>
                            <li>Check if the certificate is genuine</li>
                            <li>Try scanning the QR code instead of manual entry</li>
                            <li>Contact the issuing authority if problems persist</li>
                        </ul>
                    </div>

                    <div class="text-center mt-3">
                        <button class="btn btn-secondary" onclick="location.reload()">
                            <i class="fas fa-redo"></i> Try Again
                        </button>
                    </div>
                `);
            }

            function formatDate(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }

            // Stop scanner when tab changes
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                if (e.target.id !== 'qr-tab' && isScanning) {
                    stopScanner();
                }
            });
        });
    </script>
</body>
</html>
