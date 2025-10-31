<?php

use Illuminate\Support\Facades\Route;
use Modules\HR\Http\Controllers\EmployeesController;
use Modules\HR\Http\Controllers\AttendanceController;
use Modules\HR\Http\Controllers\DepartmentController;

/*
|--------------------------------------------------------------------------
| HR Module Routes
|--------------------------------------------------------------------------
| All HR-related routes are grouped safely here with middleware protection.
| This file isolates HR module behavior to prevent global route pollution.
*/

Route::middleware(['web', 'auth'])
    ->prefix('hr/employees')
    ->name('hr.employees.')
    ->group(function () {
        Route::get('/filter', [EmployeesController::class, 'filter'])->name('filter'); // AJAX
        Route::get('/', [EmployeesController::class, 'index'])->name('index');
        Route::get('/create', [EmployeesController::class, 'create'])->name('create');
        Route::post('/', [EmployeesController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [EmployeesController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EmployeesController::class, 'update'])->name('update');
        Route::get('/{id}', [EmployeesController::class, 'show'])->name('show');
        Route::delete('/{id}', [EmployeesController::class, 'destroy'])->name('destroy');
        Route::get('/filter', [EmployeesController::class, 'filter'])->name('filter'); // AJAX
    });

/*
|--------------------------------------------------------------------------
| Placeholder Routes for HR submodules
|--------------------------------------------------------------------------
| These are temporary views to test the sidebar accordion functionality.
| They will be replaced later by their actual module controllers.
*/

Route::middleware(['web', 'auth'])
    ->prefix('hr')
    ->group(function () {
        Route::view('/attendance', 'hr::placeholder')->name('hr.attendance.index');
        Route::view('/leaves', 'hr::placeholder')->name('hr.leaves.index');
        Route::view('/penalties', 'hr::placeholder')->name('hr.penalties.index');
        Route::view('/evaluations', 'hr::placeholder')->name('hr.evaluations.index');
        Route::view('/departments', 'hr::placeholder')->name('hr.departments.index');
        Route::view('/designations', 'hr::placeholder')->name('hr.designations.index');
        Route::view('/companies', 'hr::placeholder')->name('hr.companies.index');
        Route::view('/shifts', 'hr::placeholder')->name('hr.shifts.index');
        Route::resource('departments', DepartmentController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->names('hr.departments');


    });
Route::middleware(['web','auth'])
    ->prefix('hr/attendance')
    ->name('hr.attendance.')
    ->group(function () {
        Route::get('/',        [AttendanceController::class, 'index'])->name('index');
        Route::get('/filter',  [AttendanceController::class, 'filter'])->name('filter'); // AJAX
        Route::post('/mark',   [AttendanceController::class, 'mark'])->name('mark');
        Route::post('/import', [AttendanceController::class, 'import'])->name('import');
        Route::get('/export',  [AttendanceController::class, 'export'])->name('export');
    });

// Safe debug output for confirmation
print("[DEBUG] HR module routes loaded successfully.\n");
