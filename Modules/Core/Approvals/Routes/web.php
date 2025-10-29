<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Approvals\Http\Controllers\ApprovalController;

// ⚙️ Group for all approvals routes
Route::middleware(['web', 'auth'])->prefix('approvals')->group(function () {

    // قائمة جميع الموافقات
    Route::get('/', [ApprovalController::class, 'index'])->name('approvals.index');

    // عرض تفاصيل موافقة واحدة
    Route::get('/{id}', [ApprovalController::class, 'show'])->name('approvals.show');

    // الموافقة على الخطوة الحالية
    Route::post('/{stepId}/approveStep', [ApprovalController::class, 'approveStep'])->name('approvals.approve');

    // رفض الخطوة الحالية
    Route::post('/{stepId}/rejectStep', [ApprovalController::class, 'rejectStep'])->name('approvals.reject');
});

