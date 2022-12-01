<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Auth::routes();

Route::get('/create-manager', [\App\Http\Controllers\ManagerController::class, 'registerManager']);
Route::get('/loginManager', [\App\Http\Controllers\ManagerController::class, 'loginManager']);
Route::get('/create-employee', [\App\Http\Controllers\ManagerController::class, 'createEmployee']);
Route::get('/loginEmployee', [\App\Http\Controllers\ManagerController::class, 'loginEmployee']);
Route::get('/createRecord', [\App\Http\Controllers\ManagerController::class, 'createRecord']);
Route::get('/showRecords', [\App\Http\Controllers\ManagerController::class, 'showRecords']);
Route::get('/deleteRecordByManager', [\App\Http\Controllers\ManagerController::class, 'deleteRecordByManager']);
Route::get('/deleteRecordByEmployee', [\App\Http\Controllers\ManagerController::class, 'deleteRecordByEmployee']);
Route::get('/updateRecordByEmployee', [\App\Http\Controllers\ManagerController::class, 'updateRecordByEmployee']);
Route::get('/getManagerEmployeeRecords', [\App\Http\Controllers\ManagerController::class, 'getManagerEmployeeRecords']);
Route::get('/getByManagerAllEmployeeRecords', [\App\Http\Controllers\ManagerController::class, 'getByManagerAllEmployeeRecords']);
Route::get('/getByEmployeeRecordsByCategory', [\App\Http\Controllers\ManagerController::class, 'getByEmployeeRecordsByCategory']);
Route::get('/getByManagerRecordsByCategory', [\App\Http\Controllers\ManagerController::class, 'getByManagerRecordsByCategory']);
