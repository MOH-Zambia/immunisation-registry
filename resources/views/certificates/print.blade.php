<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $certificate->target_disease }} Vaccination Certificate - Print</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                padding: 20px;
            }

            .print-container {
                width: 100%;
                max-width: 100%;
            }

            @page {
                size: A4;
                margin: 1cm;
            }
        }

        @media screen {
            body {
                background-color: #f4f4f4;
                padding: 20px;
            }

            .print-container {
                background: white;
                max-width: 800px;
                margin: 0 auto;
                padding: 40px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }

            .print-button-container {
                max-width: 800px;
                margin: 20px auto;
                text-align: center;
            }
        }

        .certificate-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .certificate-header img {
            opacity: 0.8;
            max-width: 96px;
        }

        .certificate-header h2 {
            margin-top: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .certificate-header h3 {
            font-size: 20px;
            margin: 10px 0;
        }

        .certificate-header h4 {
            font-size: 18px;
            margin: 10px 0;
        }

        .certificate-info {
            margin-bottom: 30px;
        }

        .qr-code-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .qr-code-section img {
            max-width: 200px;
            opacity: 0.8;
        }

        .client-details {
            margin-bottom: 30px;
        }

        .client-details p {
            margin: 8px 0;
            line-height: 1.6;
        }

        .client-details strong {
            display: inline-block;
            width: 180px;
        }

        .vaccinations-table {
            width: 100%;
            margin-top: 20px;
        }

        .vaccinations-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .vaccinations-table th,
        .vaccinations-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .vaccinations-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .capitalize-text {
            text-transform: capitalize;
        }

        .footer-info {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Print Buttons (hidden when printing) -->
    <div class="print-button-container no-print">
        <button onclick="window.print()" class="btn btn-primary btn-lg">
            <i class="fas fa-print"></i> Print Certificate
        </button>
        <button onclick="window.close()" class="btn btn-secondary btn-lg ml-2">
            <i class="fas fa-times"></i> Close
        </button>
        <a href="{{ route('certificates.generatePDF', ['uuid' => $certificate->certificate_uuid]) }}"
           class="btn btn-warning btn-lg ml-2">
            <i class="fas fa-download"></i> Download PDF
        </a>
    </div>

    <!-- Certificate Content -->
    <div class="print-container">
        <!-- Header -->
        <div class="certificate-header">
            <img src="{{ url('img/android-icon-96x96.png') }}" alt="Coat of Arms">
            <h2>Republic of Zambia</h2>
            <h3>Ministry of Health</h3>
            <h4>{{ $certificate->target_disease }} Vaccination Certificate</h4>
            <p><small>Issued: {{ $certificate->created_at->format('d-M-Y H:i') }}</small></p>
        </div>

        <!-- QR Code and Certificate Info -->
        <div class="row certificate-info">
            <div class="col-md-4 qr-code-section">
                <img src="{{ url($certificate->qr_code_path) }}" alt="Certificate QR Code">
            </div>

            <div class="col-md-8">
                <p><strong>Certificate UUID:</strong> {{ $certificate->certificate_uuid }}</p>
                @if($certificate->trusted_vaccine_code)
                    <p><strong>Trusted Vaccine Code:</strong> {{ $certificate->trusted_vaccine_code }}</p>
                @endif
                @if($certificate->certificate_number)
                    <p><strong>Certificate Number:</strong> {{ $certificate->certificate_number }}</p>
                @endif
            </div>
        </div>

        <!-- Client Details -->
        <div class="client-details">
            <h5 style="margin-bottom: 15px; border-bottom: 2px solid #ddd; padding-bottom: 10px;">Client Information</h5>
            <p><strong>Last Name:</strong> <span class="capitalize-text">{{ $certificate->client['last_name'] }}</span></p>
            <p><strong>First Name:</strong> <span class="capitalize-text">{{ $certificate->client['first_name'] }}</span></p>
            @if($certificate->client['other_names'])
                <p><strong>Other Names:</strong> <span class="capitalize-text">{{ $certificate->client['other_names'] }}</span></p>
            @endif
            @if($certificate->client['NRC'])
                <p><strong>NRC:</strong> {{ $certificate->client['NRC'] }}</p>
            @endif
            @if($certificate->client['passport_number'])
                <p><strong>Passport Number:</strong> {{ $certificate->client['passport_number'] }}</p>
            @endif
            <p><strong>Sex:</strong> {{ $certificate->client['sex'] == 'M' ? 'Male' : 'Female' }}</p>
            <p><strong>Date of Birth:</strong> {{ $certificate->client['date_of_birth']->format('d-M-Y') }}</p>
        </div>

        <!-- Vaccinations Table -->
        <div class="vaccinations-table">
            <h5 style="margin-bottom: 15px; border-bottom: 2px solid #ddd; padding-bottom: 10px;">Vaccination History</h5>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Vaccine</th>
                        <th>Dose Number</th>
                        <th>Batch Number</th>
                        <th>Vaccinating Organization</th>
                        <th>Facility</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($certificate->vaccinations as $vaccination)
                        <tr>
                            <td>{{ $vaccination['date']->format('d-M-Y') }}</td>
                            <td>{{ $vaccination['vaccine']->product_name }}</td>
                            <td>{{ $vaccination['dose_number'] }}</td>
                            <td>{{ $vaccination['vaccine_batch_number'] }}</td>
                            <td>{{ $vaccination['vaccinating_organization'] }}</td>
                            <td>{{ $vaccination['facility']->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer-info">
            <p><strong>This is an official vaccination certificate issued by the Ministry of Health, Republic of Zambia</strong></p>
            <p>Certificate URL: {{ url('/certificate/' . $certificate->certificate_uuid) }}</p>
            <p>For verification, scan the QR code or visit: {{ url('/') }}</p>
        </div>
    </div>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">

    <script>
        // Auto-print on load if requested
        if (window.location.search.includes('autoprint=true')) {
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            };
        }
    </script>
</body>
</html>
