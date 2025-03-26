<?php

use App\Http\Controllers\Management\TeamController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/teams', [TeamController::class, 'index']);
Route::get('/teams/{id}', [TeamController::class, 'show']);

//// Authentication
//Route::get('/management/login', [AuthController::class, 'showLoginForm']);
//Route::post('/management/login', [AuthController::class, 'login']);
//Route::get('/management/logout', [AuthController::class, 'logout']);
//
//// Team Management
//Route::prefix('/management/team')->group(function () {
//    Route::get('/add', [TeamController::class, 'create']);
//    Route::post('/add_confirm', [TeamController::class, 'store']);
//    Route::get('/edit/{id}', [TeamController::class, 'edit']);
//    Route::post('/edit_confirm', [TeamController::class, 'update']);
//    Route::get('/search', [TeamController::class, 'search']);
//    Route::get('/delete/{id}', [TeamController::class, 'delete']);
//});
//
//// Employee Management
//Route::prefix('/management/employee')->group(function () {
//    Route::get('/add', [EmployeeController::class, 'create']);
//    Route::post('/add_confirm', [EmployeeController::class, 'store']);
//    Route::get('/edit/{id}', [EmployeeController::class, 'edit']);
//    Route::post('/edit_confirm', [EmployeeController::class, 'update']);
//    Route::get('/search', [EmployeeController::class, 'search']);
//    Route::get('/delete/{id}', [EmployeeController::class, 'delete']);
//    Route::get('/export_csv', [EmployeeController::class, 'exportCSV']);
//});
