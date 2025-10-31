<?php

use Illuminate\Support\Facades\Route;
use Modules\HR\Http\Controllers\EmployeesController;
use Modules\HR\Http\Controllers\CompaniesController;

Route::middleware(['web','auth'])
    ->prefix('hr')
    ->name('hr.')
    ->group(function () {

        Route::view('/', 'hr::index')->name('index');

        // 🧱 Employees
        Route::get('/employees', [EmployeesController::class, 'index'])->name('employees.index');
        Route::post('/employees', [EmployeesController::class, 'store'])->name('employees.store');
        Route::get('/employees/table', [EmployeesController::class, 'table'])->name('employees.table');
        Route::get('/employees/{employee}', [EmployeesController::class, 'show'])->name('employees.show');
        Route::put('/employees/{employee}', [EmployeesController::class, 'update'])->name('employees.update');
        Route::delete('/employees/{employee}', [EmployeesController::class, 'destroy'])->name('employees.destroy');
        Route::post('/employees/{employee}/documents', [EmployeesController::class, 'uploadDocument'])->name('employees.documents.upload');
        Route::delete('/employees/{employee}/documents/{doc}', [EmployeesController::class, 'deleteDocument'])->name('employees.documents.delete');
        Route::get('/employees/export/{type}', [EmployeesController::class, 'export'])->name('employees.export');

        // 🏢 Companies
        Route::get('/companies', [CompaniesController::class, 'index'])->name('companies.index');
        Route::get('/companies/table', [CompaniesController::class, 'table'])->name('companies.table');
        Route::post('/companies', [CompaniesController::class, 'store'])->name('companies.store');
        Route::get('/companies/create', [CompaniesController::class, 'create'])->name('companies.create');
        Route::delete('/companies/{company}', [CompaniesController::class, 'destroy'])->name('companies.destroy');
    });


