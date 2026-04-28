<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\ShareStatisticsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('shares.index');
});

Auth::routes();

Route::resource('forum', ForumController::class)->except(['show','destroy','create','store']);

Route::get('/shares', [ShareController::class, 'index'])->name('shares.index');
Route::post('/shares', [ShareController::class, 'store'])->name('shares.store');
Route::get('/shares/stats', [ShareController::class, 'stats'])->name('shares.stats');
Route::get('/shares/by-platform', [ShareController::class, 'byPlatform'])->name('shares.byPlatform');
Route::get('/shares/by-date', [ShareController::class, 'byDate'])->name('shares.byDate');