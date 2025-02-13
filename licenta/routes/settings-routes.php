<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;

Route::patch('/site/{site}/settings/ssl-certificate', [SettingsController::class, 'updateSslCertificate'])->name('update.certificate.settings');
Route::patch('/site/{site}/settings/uptime', [SettingsController::class, 'updateUptime'])->name('update.uptime.settings');
Route::patch('/site/{site}/settings', [SettingsController::class, 'updateGeneral'])->name('settings.general');
Route::get('/site/{site}/settings/ssl', [SettingsController::class, 'sslCertificate'])->name('settings.ssl-certificate');
Route::get('/site/{site}/settings/uptime', [SettingsController::class, 'uptime'])->name('settings.uptime');
Route::get('/site/{site}/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::put('/site/{site}/settings/uptime', [SettingsController::class, 'updateUptime'])->name('update.uptime.settings');