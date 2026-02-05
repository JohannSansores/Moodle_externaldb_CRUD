<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExternalUserController;
use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard = list external users
    Route::get('/dashboard', [ExternalUserController::class, 'index'])->name('dashboard');

    // Full CRUD for external users
    Route::resource('external-users', ExternalUserController::class)->except(['index', 'show']);
});

Route::middleware(['auth'])->prefix('catalogs')->group(function () {
    // Unified catalog page
    Route::get('/{type}', [CatalogController::class, 'show'])->name('catalogs.show');

    // CRUD actions
    Route::post('/store', [CatalogController::class, 'store'])->name('catalogs.store');
    Route::put('/{type}/{id}', [CatalogController::class, 'update'])->name('catalogs.update');
    Route::delete('/{type}/{id}', [CatalogController::class, 'destroy'])->name('catalogs.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
