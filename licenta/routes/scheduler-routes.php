<?php

use App\Http\Controllers\SchedulerController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "site/{site}/"],
  function () {
    // Route::get("/delete/{site}/{scheduler}", [
    //     \App\Http\Controllers\SchedulerController::class,
    //     "showDeleteForm"
    // ])->name("delete");

    // Route::delete("/delete/{site}/{scheduler}", [\App\Http\Controllers\SchedulerController::class, "destroy"])->name(
    // "destroy"
    // );

    // Route::put("/update/{site}/{scheduler}", [\App\Http\Controllers\SchedulerController::class, "update"])->name(
    // "update"
    // );

    Route::resource('schedulers', SchedulerController::class);
    // Route::post("/", [\App\Http\Controllers\SchedulerController::class, "store"])->name("store");
    // Route::get("/{site}/create", [\App\Http\Controllers\SchedulerController::class, "create"])->name("create");
    // Route::get("/{site}", [\App\Http\Controllers\SchedulerController::class, "index"])->name("index");
    // Route::get("/{site}/{scheduler}", [\App\Http\Controllers\SchedulerController::class, "show"])->name("show");
    // Route::get("/edit/{site}/{scheduler}", [\App\Http\Controllers\SchedulerController::class, "edit"])->name("edit");
  }
);