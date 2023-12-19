<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CsvController;
use App\Http\Controllers\Admin\GetCsvController;

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
    return view('welcome');
});
Route::get('/user', function(){
    return view('user.index');
});

Route::get('/userClass', [App\Http\Controllers\User::class, 'index']);
Route::get('admin/cities', [CsvController::class, 'index']);
Route::get('admin/cities/{id}', [CsvController::class, 'show']);
Route::get('admin/exec_deepl',  [CsvController::class, 'execDeepLearning'])->name('deepLearning.exec');
Route::prefix('admin/get_csv')->group(function() {
    Route::get('', [GetCsvController::class, 'getInfo'])->name('info.get');
    Route::post('exec', [GetCsvController::class, 'getCsv'])->name('csv.get');
});