<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\UserController;
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
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('auth.authenticate');
    Route::get('/register/{invite:uuid}', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/register/{invite:uuid}', [AuthController::class, 'store'])->name('auth.store');
    Route::get('/invitations/expired', [AuthController::class, 'expired'])->name('auth.expired');
});

Route::middleware('auth')->group(function (){
    Route::post('/logout', [AuthController::class, 'unauthenticate'])->name('auth.logout');
    Route::post('/invite', [AuthController::class, 'invite'])->name('auth.invite');
});

// dashboard
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('admin.dashboard');
    Route::get('/messages')->name('admin.messages');
    Route::get('/cm')->name('admin.cm');
    
    Route::resource('users', UserController::class)
        ->only('index', 'update', 'destroy')
        ->names([
            'index' => 'admin.users',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy'
        ]);

    Route::get('/settings')->name('admin.settings');
});