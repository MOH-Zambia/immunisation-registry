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

//Route::get('/', function () {
//    return view('index');
//});

Route::get('/', [App\Http\Controllers\IndexController::class, 'index'])->name('index');

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


Auth::routes();

Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');

Route::resource('providers', App\Http\Controllers\ProviderController::class);

Route::resource('roles', App\Http\Controllers\RoleController::class);

Route::resource('users', App\Http\Controllers\UserController::class);

Route::resource('vaccines', App\Http\Controllers\VaccineController::class);

Route::resource('clients', App\Http\Controllers\ClientController::class);

Route::resource('certificates', App\Http\Controllers\CertificateController::class);

Route::resource('facilities', App\Http\Controllers\FacilityController::class);

Route::resource('districts', App\Http\Controllers\DistrictController::class);

Route::resource('provinces', App\Http\Controllers\ProvinceController::class);

Route::resource('vaccinations', App\Http\Controllers\VaccinationController::class);

Route::resource('countries', App\Http\Controllers\CountryController::class);

Route::resource('records', App\Http\Controllers\RecordController::class);

Route::resource('facilityTypes', App\Http\Controllers\FacilityTypeController::class);

Route::resource('importLogs', App\Http\Controllers\ImportLogController::class);


// Auth::routes();















