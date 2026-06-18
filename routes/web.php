<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\IndexController::class, 'index'])->name('index');

Route::get('get_vaccination_certificate', function () {
    return view('certificates.get_vaccination_certificate');
});

Route::get('verify_vaccination_certificate', function () {
    return view('certificates.verify_vaccination_certificate');
});

Route::get('about', function () {
    return view('about');
});

Route::get('contact', function () {
    return view('contact');
});

Route::get('help', function () {
    return view('help');
});

Route::get('vaccination-centres', function () {
    return view('vaccination-centres');
});

Route::get('appointment', function () {
    return view('appointment');
});

Route::get('certificate/{uuid}', [App\Http\Controllers\CertificateController::class, 'view'])->name('certificate');
Route::post('certificates/verify', [App\Http\Controllers\CertificateController::class, 'verifyCertificate'])->name('certificates.verify');
Route::post('client/verify', [App\Http\Controllers\ClientController::class, 'verify'])->name('clients.verify');
Route::post('sendEmail', [App\Http\Controllers\Auth\OTPVerificationController::class, 'sendEmail'])->middleware('throttle:3,1')->name('sendEmail');
Route::post('sendSMS', [App\Http\Controllers\Auth\OTPVerificationController::class, 'sendSMS'])->middleware('throttle:3,1')->name('sendSMS');
Route::post('sendSMSViaZamtelBulkSMS', [App\Http\Controllers\Auth\OTPVerificationController::class, 'sendSMSViaZamtelBulkSMS'])->middleware('throttle:3,1')->name('sendSMSViaZamtelBulkSMS');
Route::post('verifyOTP', [App\Http\Controllers\Auth\OTPVerificationController::class, 'verifyOTP'])->middleware('throttle:5,1')->name('verifyOTP');

Route::get('clients/datatable', [App\Http\Controllers\ClientController::class, 'datatable'])->name('clients.datatable');
Route::post('clients/generate-certificate', [App\Http\Controllers\ClientController::class, 'generateCertificateForClient'])->name('clients.generate-certificate');
Route::get('users/datatable', [App\Http\Controllers\UserController::class, 'datatable'])->name('users.datatable');
Route::get('certificates/datatable', [App\Http\Controllers\CertificateController::class, 'datatable'])->name('certificates.datatable');
Route::get('vaccinations/datatable', [App\Http\Controllers\VaccinationController::class, 'datatable'])->name('vaccinations.datatable');
Route::get('vaccinations/export', [App\Http\Controllers\VaccinationController::class, 'export'])->name('vaccinations.export');

Route::get('certificates/generatePDF/{uuid}', [App\Http\Controllers\CertificateController::class, 'generatePDF'])->name('certificates.generatePDF');
Route::get('certificates/{id}/print', [App\Http\Controllers\CertificateController::class, 'printCertificate'])->name('certificates.print');

// Public routes for certificate viewing and printing
Route::get('certificate/{uuid}/print', [App\Http\Controllers\CertificateController::class, 'printCertificateByUuid'])->name('certificate.print');

Auth::routes(['verify' => true]);

Route::group(['middleware' => 'auth'], function(){
    //only verified account can access with this group
    Route::group(['middleware' => ['verified']], function() {
        Route::resource('clients', App\Http\Controllers\ClientController::class);
        Route::resource('certificates', App\Http\Controllers\CertificateController::class);
        Route::resource('vaccinations', App\Http\Controllers\VaccinationController::class);
        Route::resource('users', App\Http\Controllers\UserController::class);
        Route::post('ajaxRequest', [App\Http\Controllers\TrustedVaccineController::class, 'ajaxRequestPost'])->name('ajaxRequest.post');
    });

    //Only admins can access this group of routes
    Route::group(['middleware' => 'admin'], function(){
        Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        Route::get('users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::get('clients', [App\Http\Controllers\ClientController::class, 'index'])->name('clients.index');

        // SMS Testing routes
        Route::get('admin/test-sms', [App\Http\Controllers\Auth\OTPVerificationController::class, 'showTestSmsInterface'])->name('admin.test-sms');
        Route::post('admin/test-sms/send', [App\Http\Controllers\Auth\OTPVerificationController::class, 'sendTestSMS'])->name('admin.test-sms.send');

        // Log Viewer routes
        Route::get('admin/logs', [App\Http\Controllers\Admin\LogViewerController::class, 'index'])->name('admin.logs');
        Route::post('admin/logs/view', [App\Http\Controllers\Admin\LogViewerController::class, 'view'])->name('admin.logs.view');
        Route::get('admin/logs/download', [App\Http\Controllers\Admin\LogViewerController::class, 'download'])->name('admin.logs.download');
        Route::post('admin/logs/delete', [App\Http\Controllers\Admin\LogViewerController::class, 'delete'])->name('admin.logs.delete');
        Route::post('admin/logs/clear', [App\Http\Controllers\Admin\LogViewerController::class, 'clear'])->name('admin.logs.clear');

        // System Tools routes
        Route::get('admin/system-tools', [App\Http\Controllers\Admin\SystemToolsController::class, 'index'])->name('admin.system-tools');
        Route::post('admin/system-tools/generate-certificates', [App\Http\Controllers\Admin\SystemToolsController::class, 'generateCertificates'])->name('admin.system-tools.generate-certificates');
        Route::post('admin/system-tools/regenerate-missing-qrcodes', [App\Http\Controllers\Admin\SystemToolsController::class, 'regenerateMissingQrCodes'])->name('admin.system-tools.regenerate-missing-qrcodes');
        Route::post('admin/system-tools/import-dhis2', [App\Http\Controllers\Admin\SystemToolsController::class, 'importDHIS2Data'])->name('admin.system-tools.import-dhis2');
        Route::post('admin/system-tools/import-dhis2-per-facility', [App\Http\Controllers\Admin\SystemToolsController::class, 'importDHIS2DataPerFacility'])->name('admin.system-tools.import-dhis2-per-facility');
        Route::post('admin/system-tools/export-trusted-portal', [App\Http\Controllers\Admin\SystemToolsController::class, 'exportToTrustedPortal'])->name('admin.system-tools.export-trusted-portal');
        Route::post('admin/system-tools/clear-cache', [App\Http\Controllers\Admin\SystemToolsController::class, 'clearCache'])->name('admin.system-tools.clear-cache');
        Route::post('admin/system-tools/optimize', [App\Http\Controllers\Admin\SystemToolsController::class, 'optimizeApp'])->name('admin.system-tools.optimize');
        Route::get('admin/system-tools/stats', [App\Http\Controllers\Admin\SystemToolsController::class, 'getStats'])->name('admin.system-tools.stats');
        Route::get('admin/qrcode-settings', [App\Http\Controllers\Admin\QrCodeSettingsController::class, 'index'])->name('admin.qrcode-settings.index');
        Route::post('admin/qrcode-settings', [App\Http\Controllers\Admin\QrCodeSettingsController::class, 'update'])->name('admin.qrcode-settings.update');

        Route::resource('roles', App\Http\Controllers\RoleController::class);
        Route::resource('facilities', App\Http\Controllers\FacilityController::class);
        Route::resource('districts', App\Http\Controllers\DistrictController::class);
        Route::resource('provinces', App\Http\Controllers\ProvinceController::class);
        Route::resource('countries', App\Http\Controllers\CountryController::class);
        Route::resource('providers', App\Http\Controllers\ProviderController::class);
        Route::resource('vaccines', App\Http\Controllers\VaccineController::class);
        Route::resource('records', App\Http\Controllers\RecordController::class);
    });
});

















