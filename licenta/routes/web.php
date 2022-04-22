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
    // dispatch(new \App\Jobs\CrawlSite(App\Models\Site::first()))->onQueue('crawlers');
    // App\Jobs\SslCertificateWatcher::dispatch();
    // App\Jobs\UptimeMonitor::dispatch(App\Models\Site::find(7), resolve(SiteStatsRepository::class))->onQueue('uptime');
    // dd(Site::find(2)->last_incident);
    // dd(Site::first()->stats()->where('http_code', '!=', '200')->get());
    return view('welcome');
});

include 'scheduler-routes.php';
include 'settings-routes.php';

Route::get('/site/{site}/download-broken-links', [SiteController::class, 'downloadBrokenLinks'])->name('site.download-broken-links');
Route::get('site/{site}/delete', [SiteController::class, 'delete'])->name('sites.delete');
Route::get('site/{site}/overview', [SiteController::class, 'overview'])->name('sites.overview');
Route::get('/site/{site}/broken-links', [SiteController::class, 'brokenLinks'])->name('sites.broken-links');
Route::resource('sites', SiteController::class)->middleware('auth');
Route::get('uptime/{site}', function(){
    return;
})->name('uptime.index');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
