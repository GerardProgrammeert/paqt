<?php

use App\Http\Controllers\ResidentController;
use App\Http\Controllers\RideController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/residents', [ResidentController::class, 'index']);
Route::post('/residents', [ResidentController::class, 'create']);
Route::get('/{account}/rides', [RideController::class, 'index']);
Route::get('/rides', [RideController::class, 'index']);
Route::post('/rides', [RideController::class, 'store']);


