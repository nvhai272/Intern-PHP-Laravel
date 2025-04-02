<?php

use App\Http\Controllers\Management\AuthController;
use App\Http\Controllers\Management\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Management\TeamController;
use App\Models\Team;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Authentication
Route::get('/management/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/management/login', [AuthController::class, 'login'])->name('login');
Route::post('/management/logout', [AuthController::class, 'logout'])->name('logout');

// Team Management
Route::middleware(['team.middleware'])->prefix('/management/team')->group(function () {
    Route::get('/list', [TeamController::class, 'index'])->name('team.list');
    Route::get('/detail/{id}', [TeamController::class, 'show'])->name('team.detail');

    Route::get('/add', [TeamController::class, 'showCreateForm'])->name('team.form.create');
    Route::post('/add-confirm', [TeamController::class, 'confirmCreate'])->name('team.add-confirm');
    Route::post('/add', [TeamController::class, 'create'])->name('team.create');

    Route::get('/edit/{id}', [TeamController::class, 'showEditForm'])->name('team.form.edit');
    Route::post('/edit-confirm', [TeamController::class, 'confirmEdit'])->name('team.edit-confirm');
    Route::post('/edit/{id}', [TeamController::class, 'edit'])->name('team.edit');

    Route::get('/search', [TeamController::class, 'search'])->name('team.search');
    Route::delete('/delete/{id}', [TeamController::class, 'delete'])->name('team.delete');
});


// Employee Management
Route::prefix('/management/employee')->group(function () {
    Route::get('/list', [EmployeeController::class, 'index'])->name('emp.list');
    Route::get('/detail/{id}', [EmployeeController::class, 'show'])->name('emp.detail');

    Route::get('/add', [EmployeeController::class, 'showCreateForm'])->name('emp.form.create');
    Route::post('/add', [EmployeeController::class, 'create'])->name('emp.create');
    Route::post('/add-confirm', [EmployeeController::class, 'confirmCreate'])->name('emp.add-confirm');

    Route::get('/edit/{id}', [EmployeeController::class, 'showEditForm'])->name('emp.form.edit');
    Route::post('/edit/{id}', [EmployeeController::class, 'edit'])->name('emp.edit');
    Route::post('/edit-confirm', [EmployeeController::class, 'confirmEdit'])->name('emp.edit-confirm');

    Route::get('/search', [EmployeeController::class, 'search'])->name('emp.search');
    Route::get('/delete/{id}', [EmployeeController::class, 'delete'])->name('emp.delete');

    Route::get('/export-csv', [EmployeeController::class, 'exportCSV']);
});
