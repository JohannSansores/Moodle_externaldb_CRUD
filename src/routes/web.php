<?php

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExternalUserController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DatabaseExportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// ─── Rutas públicas de registro ───────────────────────────────────────────────

Route::get('/', [RegistrationController::class, 'show'])
    ->name('register.form');

Route::get('/register', [RegistrationController::class, 'show'])
    ->name('register.show');

Route::post('/register', [RegistrationController::class, 'store'])
    ->name('register.store');

Route::post('/validate-field', [RegistrationController::class, 'validateField'])
    ->name('validate.field');

Route::get('/register/success', function () {
    return view('auth.register-success');
})->name('register.success');

// ─── Login / Logout admin ─────────────────────────────────────────────────────

Route::get('/admin', [AuthenticatedSessionController::class, 'create'])
    ->name('admin.login');

Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])
    ->name('admin.login.submit');

Route::post('/admin/logout', [AuthenticatedSessionController::class, 'logout'])
    ->name('admin.logout');

// ─── Rutas solo superadmin ────────────────────────────────────────────────────

Route::middleware(['auth', 'verified', 'superadmin'])->group(function () {
    Route::resource('admins', AdminController::class)->except(['show', 'edit', 'update']);
    Route::get('/database/export', [DatabaseExportController::class, 'export'])
        ->name('database.export');
});

// ─── Rutas admin + superadmin (CRUD usuarios externos) ───────────────────────

Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    Route::get('/dashboard', [ExternalUserController::class, 'index'])
        ->name('dashboard');

    // IMPORTANTE: rutas específicas ANTES del resource para evitar
    // que {external_user} capture segmentos como "import" o "bulk-destroy"
    Route::get('/external-users/import/form',     [ExternalUserController::class, 'importForm'])
        ->name('external-users.import.form');
    Route::get('/external-users/import/template', [ExternalUserController::class, 'importTemplate'])
        ->name('external-users.import.template');
    Route::post('/external-users/import',         [ExternalUserController::class, 'import'])
        ->name('external-users.import');

    // Eliminación masiva — POST porque lleva body con IDs seleccionados
    Route::post('/external-users/bulk-destroy',   [ExternalUserController::class, 'bulkDestroy'])
        ->name('external-users.bulk.destroy');

    // Resource estándar (create, store, edit, update, destroy)
    Route::resource('external-users', ExternalUserController::class)
        ->except(['index', 'show']);

    // Catálogos
    Route::prefix('catalogs')->group(function () {
        Route::get('/{type}',    [CatalogController::class, 'show'])   ->name('catalogs.show');
        Route::post('/store',    [CatalogController::class, 'store'])  ->name('catalogs.store');
        Route::put('/{type}/{id}',    [CatalogController::class, 'update']) ->name('catalogs.update');
        Route::delete('/{type}/{id}', [CatalogController::class, 'destroy'])->name('catalogs.destroy');
    });
});

// ─── Perfil (todos los autenticados con email verificado) ────────────────────

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])   ->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update']) ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';