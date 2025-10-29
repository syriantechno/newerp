<?php
use Illuminate\Support\Facades\Route;
use Modules\Core\Approvals\Http\Controllers\ApprovalController;

Route::prefix('approvals')->group(function () {
    Route::get('/', [ApprovalController::class, 'index'])->name('approvals.index');
    Route::get('create', [ApprovalController::class, 'create'])->name('approvals.create');
    Route::post('store', [ApprovalController::class, 'store'])->name('approvals.store');
    Route::get('{id}', [ApprovalController::class, 'show'])->name('approvals.show');
    Route::post('{id}/action', [ApprovalController::class, 'action'])->name('approvals.action');
    Route::post('{stepId}/approveStep', [ApprovalController::class, 'approveStep'])->name('approvals.approve');
    Route::post('{stepId}/rejectStep', [ApprovalController::class, 'rejectStep'])->name('approvals.reject');
});
