<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('providers', App\Http\Controllers\ProviderController::class);

Route::resource('roles', App\Http\Controllers\RoleController::class);

Route::resource('users', App\Http\Controllers\UserController::class);

Route::resource('vaccines', App\Http\Controllers\VaccineController::class);

Route::resource('patients', App\Http\Controllers\PatientController::class);

Route::resource('certificates', App\Http\Controllers\CertificateController::class);

Route::resource('patientIDs', App\Http\Controllers\PatientIDController::class);

Route::resource('patientIDTypes', App\Http\Controllers\PatientIDTypeController::class);

Route::resource('facilities', App\Http\Controllers\FacilityController::class);

Route::resource('districts', App\Http\Controllers\DistrictController::class);

Route::resource('provinces', App\Http\Controllers\ProvinceController::class);

Route::resource('vaccinations', App\Http\Controllers\VaccinationController::class);

Route::resource('countries', App\Http\Controllers\CountryController::class);

Route::resource('records', App\Http\Controllers\RecordController::class);

Route::resource('providerIDs', App\Http\Controllers\ProviderIDController::class);

Route::resource('providerIDs', App\Http\Controllers\ProviderIDController::class);

Route::resource('facilityTypes', App\Http\Controllers\FacilityTypeController::class);

Route::resource('iDTypes', App\Http\Controllers\IDTypeController::class);


Route::resource('patientIDs', App\Http\Controllers\PatientIDController::class);

Route::resource('iDTypes', App\Http\Controllers\IDTypeController::class);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
