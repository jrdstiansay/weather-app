<?php

use App\Http\Controllers\PlaceController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(WeatherController::class)->group(function () {
    Route::get('weather', 'index');
});

Route::controller(PlaceController::class)->group(function () {
    Route::get('place', 'index');
});