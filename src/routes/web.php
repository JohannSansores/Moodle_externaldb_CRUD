<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExternalUserController;
use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', function () {
    return view('welcome');
});

// Superadmin routes (manage other admins)
Route::middleware(['auth', 'superadmin'])->group(function () {
    Route::resource('admins', AdminController::class)->except(['show','edit','update']);
});

// Admin & Superadmin routes (CRUD)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [ExternalUserController::class, 'index'])->name('dashboard');
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
