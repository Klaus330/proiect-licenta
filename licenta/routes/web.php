<?php

use App\Http\Controllers\SiteController;
use App\Repositories\SiteStatsRepository;
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
    App\Jobs\UptimeMonitor::dispatch(App\Models\Site::find(7), resolve(SiteStatsRepository::class));
    return view('welcome');
});

include 'scheduler-routes.php';
include 'settings-routes.php';

Route::get('site/{site}/delete', [SiteController::class, 'delete'])->name('sites.delete');
Route::get('site/{site}/overview', [SiteController::class, 'overview'])->name('sites.overview');
Route::resource('sites', SiteController::class)->middleware('auth');
Route::get('uptime/{site}', function(){
    return;
})->name('uptime.index');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
