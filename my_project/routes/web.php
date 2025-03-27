<?php

use App\Http\Controllers\Management\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Management\TeamController;
use App\Models\Team;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Kiểm tra xem global scope có hoạt động không
//    Route::get('/test-global-scope', static function () {
//    return response()->json(Team::all());
//});

// Authentication
Route::get('/management/login', [AuthController::class, 'showLoginForm']);
Route::post('/management/login', [AuthController::class, 'login'])->name('login');
Route::post('/management/logout', [AuthController::class, 'logout'])->name('logout');

// Team Management
Route::prefix('/management/team')->group(function () {

    Route::get('/list', [TeamController::class, 'index'])->name('team.list');
    Route::get('/detail/{id}', [TeamController::class, 'show'])->name('team.detail');

    Route::get('/add', [TeamController::class, 'showCreateForm'])->name('team.form.create');
    Route::post('/add-confirm', [TeamController::class, 'confirm'])->name('team.confirm');
    Route::post('/add', [TeamController::class, 'create'])->name('team.create');

    Route::get('/edit/{id}', [TeamController::class, 'showEditForm']);
    Route::post('/edit/{id}', [TeamController::class, 'edit']);
    Route::post('/edit-confirm', [TeamController::class, 'confirm']);

    Route::get('/search', [TeamController::class, 'search']);
    Route::get('/delete/{id}', [TeamController::class, 'delete']);
});

// Employee Management
Route::prefix('/management/employee')->group(function () {

    Route::get('/list', [EmployeeController::class, 'index']);
    Route::get('/detail/{id}', [EmployeeController::class, 'show']);

    Route::get('/add', [EmployeeController::class, 'showCreateForm']);
    Route::post('/add', [EmployeeController::class, 'create']);
    Route::post('/add-confirm', [EmployeeController::class, 'confirm']);

    Route::get('/edit/{id}', [EmployeeController::class, 'showEditForm']);
    Route::post('/edit/{id}', [EmployeeController::class, 'edit']);
    Route::post('/edit-confirm', [EmployeeController::class, 'confirm']);

    Route::get('/search', [EmployeeController::class, 'search']);
    Route::get('/delete/{id}', [EmployeeController::class, 'delete']);

    Route::get('/export-csv', [EmployeeController::class, 'exportCSV']);
});
