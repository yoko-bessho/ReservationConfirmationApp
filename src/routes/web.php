<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\DiffHistoryController;


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

Route::get('/', [DiffHistoryController::class, 'index'])->name('index');
Route::get('/diff/check', [DiffHistoryController::class, 'check'])
    ->name('diff.check');


Route::get('/import', [ReservationController::class, 'showImportForm'])->name('importForm');
Route::post('/import', [ReservationController::class, 'import'])->name('import');


