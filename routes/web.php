<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\GetInfoFetchCsvController;
use App\Http\Controllers\Admin\ExecFetchCsvController;
use App\Http\Controllers\GetInfoDeepLearningController;
use App\Http\Controllers\EexcDeepLearningController;

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

Route::get('/', function () {return view('index');})->name('home');
Route::get('/user', function(){
    return view('user.index');
});

Route::get('/userClass', [App\Http\Controllers\User::class, 'index']);

Route::prefix('get_deeplearning')->group(function() {
    Route::get('',  [GetInfoDeepLearningController::class, 'getInfoDeepLearning'])->name('deepLearningInfo.get');
    Route::get('exec',  [EexcDeepLearningController::class, 'execDeepLearning'])->name('deepLearning.exec');
});
Route::prefix('admin/fetch_csv')->group(function() {
    Route::get('', [GetInfoFetchCsvController::class, 'getInfo'])->name('info.get');
    Route::post('exec', [ExecFetchCsvController::class, 'fetchCsv'])->name('csv.get');
});