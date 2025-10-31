<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\{
    HomeController,
    InfoUserController,
    RegisterController,
    ResetController,
    SessionsController,
    ChangePasswordController,
    SettingsController,
    ModulesSyncController,
    ModuleToggleController,
    NotificationController
};

// Settings Subcontrollers
use App\Http\Controllers\Settings\{
    MessagesController,
    CustomFieldController,
    UserController,
    UserRoleController
};
use App\Http\Controllers\Settings\Rbac\{
    RoleController,
    PermissionMatrixController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
// ===================== PROFILE & USER PROFILE =====================
Route::middleware(['auth'])->group(function () {

    // صفحة البروفايل العامة (عرض البيانات)
    Route::get('/profile', fn() => view('profile'))->name('profile');

    // صفحة بروفايل المستخدم (القابلة للتعديل)
    Route::get('/user-profile', [InfoUserController::class, 'create'])
        ->name('user.profile');
    Route::post('/user-profile', [InfoUserController::class, 'store'])
        ->name('user.profile.store');
});

// ===================== AUTHENTICATED ROUTES =====================
Route::middleware(['web', 'auth'])->group(function () {

    /* ---------- Dashboard & Core Pages ---------- */
    Route::get('/', [HomeController::class, 'home']);
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/billing', fn() => view('billing'))->name('billing');
    Route::get('/profile', fn() => view('profile'))->name('profile');
    Route::get('/rtl', fn() => view('rtl'))->name('rtl');
    Route::get('/tables', fn() => view('tables'))->name('tables');
    Route::get('/virtual-reality', fn() => view('virtual-reality'))->name('virtual-reality');
    Route::get('/static-sign-in', fn() => view('static-sign-in'))->name('sign-in');
    Route::get('/static-sign-up', fn() => view('static-sign-up'))->name('sign-up');
// ✅ User Management (صفحة المستخدمين الأصلية)
    Route::get('/user-management', function () {
        return view('laravel-examples.user-management');
    })->name('user-management');

    /* ---------- Settings Main Page ---------- */
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');

    /* ---------- Modules ---------- */
    Route::post('/settings/modules/sync', [ModulesSyncController::class, 'sync'])->name('modules.sync');
    Route::post('/settings/modules/toggle/{id}', [ModuleToggleController::class, 'toggle'])->name('modules.toggle');

    /* ---------- Messages ---------- */
    Route::post('/settings/messages/save', [MessagesController::class, 'save'])->name('settings.messages.save');

    /* ---------- Custom Fields ---------- */
    Route::prefix('settings/custom-fields')->name('settings.custom-fields.')->group(function () {
        Route::get('/', [CustomFieldController::class, 'index'])->name('index');
        Route::get('/create', [CustomFieldController::class, 'create'])->name('create');
        Route::post('/store', [CustomFieldController::class, 'store'])->name('store');
        Route::delete('/{id}', [CustomFieldController::class, 'destroy'])->name('destroy');
    });

    /* ---------- Users & Roles ---------- */
    Route::prefix('settings')->name('settings.')->group(function () {

        // Users CRUD
        Route::post('/users/add', [UserController::class, 'store'])->name('users.add');
        Route::post('/users/{user}/update', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.delete');

        // Role assignment
        Route::post('/users/assign-role', [UserRoleController::class, 'assign'])
            ->name('users.assignRole')
            ->middleware('perm:settings,edit');

        // Roles CRUD
        Route::get('/roles', [RoleController::class, 'index'])
            ->name('roles.index')->middleware('perm:settings,view');
        Route::post('/roles', [RoleController::class, 'store'])
            ->name('roles.store')->middleware('perm:settings,add');
        Route::put('/roles/{role}', [RoleController::class, 'update'])
            ->name('roles.update')->middleware('perm:settings,edit');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
            ->name('roles.destroy')->middleware('perm:settings,delete');

        // Permissions Matrix
        Route::get('/permissions/matrix/{role?}', [PermissionMatrixController::class, 'index'])
            ->name('permissions.matrix')->middleware('perm:settings,view');
        Route::post('/permissions/matrix/{role}', [PermissionMatrixController::class, 'sync'])
            ->name('permissions.sync')->middleware('perm:settings,edit');
    });

    /* ---------- Notifications ---------- */
    Route::get('/notifications', function () {
        $notifications = \DB::table('notifications')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();
        return view('notifications.index', compact('notifications'));
    })->name('notifications.index');

    Route::post('/notifications/read', function () {
        \DB::table('notifications')
            ->where('user_id', auth()->id())
            ->update(['is_read' => true]);
        return back()->with('success', 'All notifications marked as read.');
    })->name('notifications.read');

    Route::post('/notifications/delete', function () {
        \DB::table('notifications')
            ->where('user_id', auth()->id())
            ->delete();
        return back()->with('success', 'All notifications deleted.');
    })->name('notifications.delete');

    Route::get('/notifications/check', [NotificationController::class, 'checkNew'])
        ->name('notifications.check');
});

// ===================== AUTH ROUTES =====================
Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/session', [SessionsController::class, 'store']);
    Route::get('/login/forgot-password', [ResetController::class, 'create']);
    Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
    Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
    Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

// Logout route
Route::get('/logout', [SessionsController::class, 'destroy']);

// ===================== SYSTEM ROUTES =====================
Route::get('/_ping', fn() => 'Laravel OK from main project');

// External module routes
require base_path('Modules/Core/Approvals/Routes/web.php');
