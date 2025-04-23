<?php

use App\Http\Controllers\Management\AuthController;
use App\Http\Controllers\Management\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Management\ProjectController;
use App\Http\Controllers\Management\TaskController;
use App\Http\Controllers\Management\TeamController;
use App\Http\Middleware\CheckEmployeePreviousUrl;
use App\Http\Middleware\CheckProjectPreviousUrl;
use App\Http\Middleware\CheckTaskPreviousUrl;
use App\Http\Middleware\CheckTeamPreviousUrl;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
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
    // ->middleware(TimeoutMiddleware::class);
    Route::get('/detail/{id}', [TeamController::class, 'show'])->name('team.detail');

    Route::get('/add', [TeamController::class, 'showCreateForm'])->name('team.form.create')
        ->middleware(CheckTeamPreviousUrl::class);

    Route::post('/add-confirm', [TeamController::class, 'confirmCreate'])->name('team.add-confirm');
    Route::post('/add', [TeamController::class, 'create'])->name('team.create');

    Route::get('/edit/{id}', [TeamController::class, 'showEditForm'])->name('team.form.edit')
        ->middleware(CheckTeamPreviousUrl::class);

    Route::post('/edit-confirm', [TeamController::class, 'confirmEdit'])->name('team.edit-confirm');
    Route::post('/edit/{id}', [TeamController::class, 'edit'])->name('team.edit');

    Route::get('/search', [TeamController::class, 'search'])->name('team.search');
    Route::post('/delete/{id}', [TeamController::class, 'delete'])->name('team.delete');
});


// Employee Management
Route::middleware(['emp.middleware'])->prefix('/management/employee')->group(function () {
    Route::get('/list', [EmployeeController::class, 'index'])->name('emp.list');
    Route::get('/detail/{id}', [EmployeeController::class, 'show'])->name('emp.detail');

    Route::get('/add', [EmployeeController::class, 'showCreateForm'])->name('emp.form.create')
        ->middleware(CheckEmployeePreviousUrl::class);

    Route::post('/add-confirm', [EmployeeController::class, 'confirmCreate'])->name('emp.add-confirm');
    Route::post('/add', [EmployeeController::class, 'create'])->name('emp.create');

    Route::get('/edit/{id}', [EmployeeController::class, 'showEditForm'])->name('emp.form.edit')
        ->middleware(CheckEmployeePreviousUrl::class);

    Route::post('/edit-confirm', [EmployeeController::class, 'confirmEdit'])->name('emp.edit-confirm');
    Route::post('/edit/{id}', [EmployeeController::class, 'edit'])->name('emp.edit');

    Route::get('/search', [EmployeeController::class, 'search'])->name('emp.search');
    Route::post('/delete/{id}', [EmployeeController::class, 'delete'])->name('emp.delete');

    Route::get('/export-csv', [EmployeeController::class, 'exportCSV'])->name('emp.export-csv');
});

// Project Management
Route::middleware(['management.middleware'])->prefix('/management/project')->group(function () {
    Route::get('/list', [ProjectController::class, 'index'])->name('project.list');
    // ->middleware(TimeoutMiddleware::class);
    Route::get('/detail/{id}', [ProjectController::class, 'show'])->name('project.detail');

    Route::get('/add', [ProjectController::class, 'showCreateForm'])->name('project.form.create')
        ->middleware(CheckProjectPreviousUrl::class)
    ;

    Route::post('/add-confirm', [ProjectController::class, 'confirmCreate'])->name('project.add-confirm');
    Route::post('/add', [ProjectController::class, 'create'])->name('project.create');

    Route::get('/edit/{id}', [ProjectController::class, 'showEditForm'])->name('project.form.edit')
        ->middleware(CheckProjectPreviousUrl::class)
    ;

    Route::post('/edit-confirm', [ProjectController::class, 'confirmEdit'])->name('project.edit-confirm');
    Route::post('/edit/{id}', [ProjectController::class, 'edit'])->name('project.edit');

    Route::get('/search', [ProjectController::class, 'search'])->name('project.search');
    Route::post('/delete/{id}', [ProjectController::class, 'delete'])->name('project.delete');

    Route::get('/{project}/add-team', [ProjectController::class, 'addTeam'])->name('project.add-team');
    Route::post('/{project}/confirm-add-team', [ProjectController::class, 'confirmAddTeam'])->name('project.confirm-add-team');
    Route::post('/{project}/confirm-delete-team', [ProjectController::class, 'confirmDeleteTeam'])->name('project.confirm-delete-team');

    Route::get('/{project}/search-employee', [ProjectController::class, 'searchEmployee'])->name('project.search-emp');
    Route::get('/{project}/add-employee', [ProjectController::class, 'addEmployee'])->name('project.add-emp');
    Route::post('/{project}/confirm-add-employee', [ProjectController::class, 'confirmAddEmployee'])->name('project.confirm-add-emp');
    Route::post('/{project}/confirm-delete-employee', [ProjectController::class, 'confirmDeleteEmployee'])->name('project.confirm-delete-emp');

    Route::get('/{project}/add-task', [ProjectController::class, 'addTask'])->name('project.add-task');
    Route::post('/{project}/confirm-add-task', [ProjectController::class, 'confirmAddTask'])->name('project.confirm-add-task');

});

// Task Management
Route::middleware(['management.middleware'])->prefix('/management/task')->group(function () {
    Route::get('/list', [TaskController::class, 'index'])->name('task.list');
    // ->middleware(TimeosutMiddleware::class);
    Route::get('/detail/{id}', [TaskController::class, 'show'])->name('task.detail');

    Route::get('/add', [TaskController::class, 'showCreateForm'])->name('task.form.create')
        ->middleware(CheckTaskPreviousUrl::class)
    ;

    Route::post('/add-confirm', [TaskController::class, 'confirmCreate'])->name('task.add-confirm');
    Route::post('/add', [TaskController::class, 'create'])->name('task.create');

    Route::get('/edit/{id}', [TaskController::class, 'showEditForm'])->name('task.form.edit')
        ->middleware(CheckTaskPreviousUrl::class)
    ;

    Route::post('/edit-confirm', [TaskController::class, 'confirmEdit'])->name('task.edit-confirm');
    Route::post('/edit/{id}', [TaskController::class, 'edit'])->name('task.edit');

    Route::get('/search', [TaskController::class, 'search'])->name('task.search');
    Route::post('/delete/{id}', [TaskController::class, 'delete'])->name('task.delete');
});

// Route::get('/coreui-select', function (HttpRequest $request) {
//     $token = $request->query('token');
//     $data = Cache::get( $token);
//     return view('layouts.coreui-dropdown-select-search', compact('data'));
// });


// Route::get('/coreui-select', function () {
//     $employees = [
//         ['id' => 1, 'name' => 'Nguyễn Văn A'],
//         ['id' => 2, 'name' => 'Trần Thị B'],
//         ['id' => 3, 'name' => 'Lê Văn C'],
//         ['id' => 4, 'name' => 'Phạm Thị D'],
//     ];
//     return view('layouts.coreui-dropdown-select-search', compact('employees'));
// });
