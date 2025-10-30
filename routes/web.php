<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\CustomFieldController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ModulesSyncController;
use App\Http\Controllers\ModuleToggleController;
use App\Http\Controllers\Settings\MessagesController;
use App\Http\Controllers\Settings\Rbac\RoleController;
use App\Http\Controllers\Settings\Rbac\PermissionMatrixController;

use App\Http\Controllers\Settings\UserRoleController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/settings/modules/sync', [ModulesSyncController::class, 'sync'])
        ->name('modules.sync');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/settings/modules/toggle/{id}', [ModuleToggleController::class, 'toggle'])
        ->name('modules.toggle');
});

Route::middleware(['web', 'auth'])->group(function () {
    // صفحة الإعدادات نفسها
    Route::get('settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');

    // حفظ تعديلات الرسائل
    Route::post('settings/messages/save', [MessagesController::class, 'save'])->name('settings.messages.save');
});

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home']);
	Route::get('dashboard', function () {
		return view('dashboard');
	})->name('dashboard');

	Route::get('billing', function () {
		return view('billing');
	})->name('billing');

	Route::get('profile', function () {
		return view('profile');
	})->name('profile');

	Route::get('rtl', function () {
		return view('rtl');
	})->name('rtl');

	Route::get('user-management', function () {
		return view('laravel-examples/user-management');
	})->name('user-management');

	Route::get('tables', function () {
		return view('tables');
	})->name('tables');

    Route::get('virtual-reality', function () {
		return view('virtual-reality');
	})->name('virtual-reality');

    Route::get('static-sign-in', function () {
		return view('static-sign-in');
	})->name('sign-in');

    Route::get('static-sign-up', function () {
		return view('static-sign-up');
	})->name('sign-up');

    Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::post('/user-profile', [InfoUserController::class, 'store']);
    Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');
});

Route::prefix('settings/custom-fields')->name('settings.custom-fields.')->group(function () {
    Route::get('/', [CustomFieldController::class, 'index'])->name('index');
    Route::get('/create', [CustomFieldController::class, 'create'])->name('create');
    Route::post('/store', [CustomFieldController::class, 'store'])->name('store');
    Route::delete('/{id}', [CustomFieldController::class, 'destroy'])->name('destroy');
});


Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');

});

Route::get('/login', function () {
    return view('session/login-session');
})->name('login');
require base_path('Modules/Core/Approvals/Routes/web.php');
Route::get('/_ping', function () {
    return 'Laravel OK from main project';
});

Route::middleware(['web', 'auth'])->group(function () {

    // عرض جميع الإشعارات
    Route::get('/notifications', function () {
        $notifications = \DB::table('notifications')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('notifications.index', compact('notifications'));
    })->name('notifications.index');

    // تحديد كمقروء
    Route::post('/notifications/read', function (Request $request) {
        \DB::table('notifications')
            ->where('user_id', auth()->id())
            ->update(['is_read' => true]);
        return back()->with('success', 'All notifications marked as read.');
    })->name('notifications.read');

    // حذف الكل
    Route::post('/notifications/delete', function (Request $request) {
        \DB::table('notifications')
            ->where('user_id', auth()->id())
            ->delete();
        return back()->with('success', 'All notifications deleted.');
    })->name('notifications.delete');
});
Route::get('/notifications/check', [App\Http\Controllers\NotificationController::class, 'checkNew'])
    ->middleware('auth')
    ->name('notifications.check');

Route::middleware(['web', 'auth'])->group(function () {

    // 🔧 صفحة الإعدادات الرئيسية
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])
        ->name('settings.index');

    // 🧩 نظام الأدوار والصلاحيات (Roles & Permissions)
    Route::prefix('settings')->name('settings.')->group(function () {

        // Roles CRUD
        Route::get('/roles', [RoleController::class, 'index'])
            ->name('roles.index')
            ->middleware('perm:settings,view');

        Route::post('/roles', [RoleController::class, 'store'])
            ->name('roles.store')
            ->middleware('perm:settings,add');

        Route::put('/roles/{role}', [RoleController::class, 'update'])
            ->name('roles.update')
            ->middleware('perm:settings,edit');

        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
            ->name('roles.destroy')
            ->middleware('perm:settings,delete');

        // Permissions Matrix
        Route::get('/permissions/matrix/{role?}', [PermissionMatrixController::class, 'index'])
            ->name('permissions.matrix')
            ->middleware('perm:settings,view');

        Route::post('/permissions/matrix/{role}', [PermissionMatrixController::class, 'sync'])
            ->name('permissions.sync')
            ->middleware('perm:settings,edit');

        Route::post('/users/assign-role', [UserRoleController::class, 'assign'])
            ->name('users.assignRole')
            ->middleware(['auth','perm:settings,edit']);
    });
});

