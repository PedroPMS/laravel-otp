<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/add-2fa', [App\Http\Controllers\Auth\Add2FaController::class, 'add2Fa'])->name('add2Fa');
Route::get('/complete-activation', [App\Http\Controllers\Auth\Add2FaController::class, 'confirm2FaActivation'])->name('complete-activation');

Route::get('/need-2fa-route', function () {
    return view('need2fa');
})->name('need-2fa-route')->middleware('2fa');

Route::post('/2fa', function () {
    return redirect('home');
})->name('2fa')->middleware('2fa');
