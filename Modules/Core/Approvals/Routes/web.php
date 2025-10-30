<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Approvals\Http\Controllers\ApprovalController;

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
    Route::get('/approvals/create', [ApprovalController::class, 'create'])->name('approvals.create');
    Route::post('/approvals', [ApprovalController::class, 'store'])->name('approvals.store');
    Route::get('/approvals/{id}', [ApprovalController::class, 'show'])->name('approvals.show');
    Route::post('/approvals/{id}/action', [ApprovalController::class, 'action'])->name('approvals.action');
});
