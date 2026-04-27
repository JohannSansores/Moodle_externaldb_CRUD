<?php

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExternalUserController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DatabaseExportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Public authentication routes
Route::get('/', [RegistrationController::class, 'show'])
    ->name('register.form');

Route::get('/register', [RegistrationController::class, 'show'])
    ->name('register.show');

Route::post('/register', [RegistrationController::class, 'store'])
    ->name('register.store');

// Email verification routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    $request->fulfill();
    return redirect()->route('dashboard')->with('status', '¡Tu correo ha sido verificado exitosamente!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'Se ha enviado un nuevo enlace de verificaci\u00f3n a tu correo.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Login admin
Route::get('/admin', [AuthenticatedSessionController::class, 'create'])
    ->name('admin.login');

Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])
    ->name('admin.login.submit');

Route::post('/admin/logout', [AuthenticatedSessionController::class, 'logout'])
    ->name('admin.logout');

// Superadmin routes
Route::middleware(['auth', 'verified', 'superadmin'])->group(function () {
    Route::resource('admins', AdminController::class)->except(['show', 'edit', 'update']);
    Route::get('/database/export', [DatabaseExportController::class, 'export'])->name('database.export');
});

// Admin & Superadmin routes (CRUD)
Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    Route::get('/dashboard', [ExternalUserController::class, 'index'])->name('dashboard');

    // Rutas de importación deben ir ANTES del resource para evitar conflicto con {external_user}
    Route::get('/external-users/import/form', [ExternalUserController::class, 'importForm'])->name('external-users.import.form');
    Route::get('/external-users/import/template', [ExternalUserController::class, 'importTemplate'])->name('external-users.import.template');
    Route::post('/external-users/import', [ExternalUserController::class, 'import'])->name('external-users.import');

    Route::resource('external-users', ExternalUserController::class)->except(['index', 'show']);

    Route::prefix('catalogs')->group(function () {
        Route::get('/{type}', [CatalogController::class, 'show'])->name('catalogs.show');
        Route::post('/store', [CatalogController::class, 'store'])->name('catalogs.store');
        Route::put('/{type}/{id}', [CatalogController::class, 'update'])->name('catalogs.update');
        Route::delete('/{type}/{id}', [CatalogController::class, 'destroy'])->name('catalogs.destroy');
    });
});

// Profile routes (all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
