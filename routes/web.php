<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Site\SiteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// main site
Route::get('/', [SiteController::class, 'index'])->name('site.index');
Route::get('/about', [SiteController::class, 'about'])->name('site.about');
Route::get('/policies', [SiteController::class, 'policies'])->name('site.policies');
Route::get('/contacts', [SiteController::class, 'contacts'])->name('site.contacts');

// authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'login'])->name('auth.login');
    Route::post('/authenticate', [UserController::class, 'authenticate'])->name('auth.authenticate');
});

// dashboard
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('admin.dashboard');
});