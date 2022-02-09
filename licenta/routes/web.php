<?php

use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SiteController;
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

Route::patch('site/{site}/settings/ssl-certificate', [SettingsController::class, 'updateSslCertificate'])->name('update.certificate.settings');
Route::patch('site/{site}/settings/uptime', [SettingsController::class, 'updateUptime'])->name('update.uptime.settings');
Route::patch('site/{site}/settings', [SettingsController::class, 'updateGeneral'])->name('settings.general');
Route::get('site/{site}/settings/ssl', [SettingsController::class, 'sslCertificate'])->name('settings.ssl-certificate');
Route::get('site/{site}/settings/uptime', [SettingsController::class, 'uptime'])->name('settings.uptime');
Route::get('site/{site}/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::get('site/{site}/delete', [SiteController::class, 'delete'])->name('sites.delete');
Route::resource('sites', SiteController::class)->middleware('auth');


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
