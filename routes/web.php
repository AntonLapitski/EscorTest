<?php

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

Route::get('/create-manager', [\App\Http\Controllers\ManagerController::class, 'registerManager']);
Route::get('/loginManager', [\App\Http\Controllers\ManagerController::class, 'loginManager']);
Route::get('/create-employee', [\App\Http\Controllers\ManagerController::class, 'createEmployee'])->middleware('auth:api');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
