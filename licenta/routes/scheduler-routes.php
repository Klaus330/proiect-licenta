<?php

use App\Http\Controllers\RemoteCodeController;
use App\Http\Controllers\SchedulerController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "site/{site}/", 'middleware' => 'auth'], function () {

    Route::resource('schedulers', SchedulerController::class);
    Route::get('/schedulers/{scheduler}/settings', [SchedulerController::class, 'settings'])->name('schedulers.settings');
    Route::post('/schedulers/{scheduler}/settings', [SchedulerController::class, 'saveSettings'])->name('schedulers.settings.save');
    Route::post('/remote-code/upload', [RemoteCodeController::class, 'create'])->name('remote-code.upload');
  }
);