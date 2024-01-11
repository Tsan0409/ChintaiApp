<?php

use App\Http\Controllers\API\CityController;
use App\Http\Controllers\API\CityCsvFileController;

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('API/get_cities', [CityController::class, 'getCitiesByPrefecture'])->name('api.get_cities');
Route::get('API/get_room_plans', [CityCsvFileController::class, 'getRoomPlans'])->name('api.get_room_plans');