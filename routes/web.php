<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Notifications
    Route::post('/notifications/read', function (Request $request) {
        /** @var User $user */
        $user = $request->user();
        $user->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.read');

    Route::get('/notifications/poll', function (Request $request) {
        /** @var User $user */
        $user   = $request->user();
        $unread = $user->unreadNotifications;
        return response()->json([
            'count'  => $unread->count(),
            'latest' => $unread->take(5)->map(fn($n) => array_merge($n->data, ['id' => $n->id])),
        ]);
    })->name('notifications.poll');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.remove-avatar');

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/import', [ImportController::class, 'index'])->name('import.index');
        Route::post('/import/upload', [ImportController::class, 'upload'])->name('import.upload');
        Route::get('/import/records', [ImportController::class, 'records'])->name('import.records');
        Route::get('/import/sample', [ImportController::class, 'sample'])->name('import.sample');
        Route::delete('/import/{batch}', [ImportController::class, 'destroy'])->name('import.destroy');
    });

    // Manager routes (admin can also access)
    Route::middleware('role:admin|manager')->prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', [ManagerController::class, 'index'])->name('dashboard');
    });

    // User routes
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    });
});
