<?php

use Illuminate\Support\Facades\Route;
use Modules\HR\Http\Controllers\EmployeeController;

Route::middleware('web')->prefix('hr')->group(function () {
    Route::get('/', [EmployeeController::class, 'index'])->name('hr.employees.index');
    Route::get('/create', [EmployeeController::class, 'create'])->name('hr.employees.create');
    Route::post('/store', [EmployeeController::class, 'store'])->name('hr.employees.store');
});
