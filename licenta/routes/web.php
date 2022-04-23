<?php

use App\Http\Controllers\SiteController;
use App\Models\Site;
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
    return view('welcome');
});

Route::group(['middleware' => 'auth:sanctum'], function(){
    include 'scheduler-routes.php';
    include 'settings-routes.php';
    
    Route::get('site/{site}/performance', [SiteController::class, 'performance'])->name('sites.performance');
    Route::get('/site/{site}/ssl-certificate-health', [SiteController::class, 'sslCertificateHealth'])->name('site.ssl-certificate-health');
    Route::get('/site/{site}/download-broken-links', [SiteController::class, 'downloadBrokenLinks'])->name('site.download-broken-links');
    Route::get('site/{site}/delete', [SiteController::class, 'delete'])->name('sites.delete');
    Route::get('site/{site}/overview', [SiteController::class, 'overview'])->name('sites.overview');
    Route::get('/site/{site}/broken-links', [SiteController::class, 'brokenLinks'])->name('sites.broken-links');
    Route::resource('sites', SiteController::class)->middleware('auth');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
