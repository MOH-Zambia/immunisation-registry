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

Route::get('/get_vaccination_certificate', function () {
    return view('get_vaccination_certificate');
});

Route::get('/verify_vaccination_certificate', function () {
    return view('verify_vaccination_certificate');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/help', function () {
    return view('help');
});

Route::get('/certificate/{uuid}', [App\Http\Controllers\CertificateController::class, 'view'])->name('certificate');
Route::post('verify_client', [App\Http\Controllers\ClientController::class, 'verify'])->name('clients.verify');
Route::post('sendOTP', [App\Http\Controllers\Auth\OTPVerificationController::class, 'sendOTP'])->name('sendOTP');
Route::post('verifyOTP', [App\Http\Controllers\Auth\OTPVerificationController::class, 'verifyOTP'])->name('verifyOTP');

Route::get('clients/datatable', [App\Http\Controllers\ClientController::class, 'datatable'])->name('clients.datatable');
Route::get('users/datatable', [App\Http\Controllers\UserController::class, 'datatable'])->name('users.datatable');
Route::get('certificates/datatable', [App\Http\Controllers\CertificateController::class, 'datatable'])->name('certificates.datatable');
Route::get('vaccinations/datatable', [App\Http\Controllers\VaccinationController::class, 'datatable'])->name('vaccinations.datatable');

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
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

        Route::get('users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');


        Route::get('/clients', [App\Http\Controllers\ClientController::class, 'index'])->name('clients.index');


        Route::resource('roles', App\Http\Controllers\RoleController::class);
        Route::resource('facilities', App\Http\Controllers\FacilityController::class);
        Route::resource('districts', App\Http\Controllers\DistrictController::class);
        Route::resource('provinces', App\Http\Controllers\ProvinceController::class);
        Route::resource('countries', App\Http\Controllers\CountryController::class);
        Route::resource('providers', App\Http\Controllers\ProviderController::class);
        Route::resource('vaccines', App\Http\Controllers\VaccineController::class);
        Route::resource('records', App\Http\Controllers\RecordController::class);
        Route::resource('importLogs', App\Http\Controllers\ImportLogController::class);
    });
});

















