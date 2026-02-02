# Certificate Verification Feature

## Overview
Implemented a comprehensive certificate verification system that allows third parties (employers, border control, venues) to validate the authenticity of COVID-19 vaccination certificates.

## Features Implemented

### 1. Verification Interface (`resources/views/certificates/verify_vaccination_certificate.blade.php`)
- **QR Code Scanner**: HTML5-based QR code scanner using html5-qrcode library
  - Camera access with user permission
  - Real-time QR code detection
  - Visual feedback for scan status
  - Start/stop scanner controls

- **Manual UUID Input**: Fallback option for accessibility
  - UUID validation
  - Format hints for users
  - Input sanitization

- **Results Display**:
  - Certificate validity status (Valid/Expired/Invalid)
  - Certificate holder information
  - Vaccination details
  - Issuance and expiration dates
  - Link to view full certificate
  - Security-conscious data masking for sensitive fields

### 2. API Endpoint (`app/Http/Controllers/CertificateController.php`)
**Method**: `verifyCertificate(Request $request)`

**Features**:
- Input validation with Laravel's validator
- UUID-based certificate lookup
- Automatic expiration checking
- Comprehensive logging with unique request IDs
- Security measures:
  - Masked sensitive data (NRC, passport numbers)
  - IP address and user agent logging for audit trail
  - Error handling without information disclosure

**Request Format**:
```json
POST /certificates/verify
{
  "certificate_uuid": "550e8400-e29b-41d4-a716-446655440000"
}
```

**Response Format** (Success):
```json
{
  "success": true,
  "message": "Certificate verified successfully",
  "data": {
    "certificate_uuid": "550e8400-e29b-41d4-a716-446655440000",
    "certificate_status": "Valid",
    "target_disease": "COVID-19",
    "trusted_vaccine_code": "AZ-123",
    "certificate_expiration_date": "2025-12-31",
    "certificate_url": "https://...",
    "created_at": "2024-01-15",
    "client": {
      "first_name": "John",
      "last_name": "Doe",
      "date_of_birth": "1990-01-01",
      "NRC": "123***89"
    }
  }
}
```

**Response Format** (Error):
```json
{
  "success": false,
  "message": "Certificate not found. Please verify the UUID and try again."
}
```

### 3. Route Configuration (`routes/web.php`)
Added route:
```php
Route::post('certificates/verify', [CertificateController::class, 'verifyCertificate'])
    ->name('certificates.verify');
```

## Security Features

1. **Data Masking**: Sensitive information (NRC, passport) is partially masked in responses
2. **Audit Logging**: All verification attempts are logged with:
   - Unique request ID
   - UUID being verified
   - IP address
   - User agent
   - Timestamp
   - Result (found/not found)
   - Certificate holder information

3. **Input Validation**: UUID format validation prevents injection attacks
4. **Error Handling**: Generic error messages prevent information disclosure
5. **CSRF Protection**: Built-in Laravel CSRF protection for POST requests

## Usage Scenarios

### For Employers
Verify employee vaccination status before allowing workplace entry.

### For Border Control
Validate traveler vaccination certificates at entry points.

### For Event Venues
Check attendee vaccination status at large gatherings.

### For Healthcare Facilities
Verify patient vaccination records.

## How to Test

### Using QR Scanner:
1. Navigate to `/verify_vaccination_certificate`
2. Click "Start Scanner"
3. Allow camera permissions
4. Point camera at certificate QR code
5. View verification results

### Using Manual Input:
1. Navigate to `/verify_vaccination_certificate`
2. Click "Manual Input" tab
3. Enter certificate UUID
4. Click "Verify Certificate"
5. View verification results

## Log Files
Verification logs are written to the `sms` channel configured in Laravel's logging system.

Sample log entry:
```
[VERIFY-abc123] Certificate verification request
{
  "uuid": "550e8400-e29b-41d4-a716-446655440000",
  "ip_address": "192.168.1.1",
  "user_agent": "Mozilla/5.0..."
}

[VERIFY-abc123] Certificate verified successfully
{
  "uuid": "550e8400-e29b-41d4-a716-446655440000",
  "client_id": 123,
  "status": "Valid",
  "holder_name": "John Doe"
}
```

## Future Enhancements (Optional)

1. **Rate Limiting**: Add throttling to prevent abuse
2. **Batch Verification**: Allow verification of multiple certificates
3. **API Key Authentication**: For programmatic access by authorized systems
4. **Verification History**: Track who verified which certificates
5. **QR Code Generation**: Auto-generate QR codes for new certificates
6. **Multi-language Support**: Internationalization for verification interface
7. **Mobile App Integration**: Native mobile app support
8. **Offline Verification**: Download certificate database for offline validation

## Dependencies

### Frontend:
- Bootstrap 4.5.0
- Font Awesome 5.14.0
- jQuery 3.6.0
- html5-qrcode 2.3.8

### Backend:
- Laravel Framework
- Carbon (date handling)
- Laravel Logging

## Browser Compatibility
- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+

**Note**: QR scanner requires HTTPS for camera access (or localhost for development).
