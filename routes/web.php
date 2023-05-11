<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\ChatController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\DataManagementController;
use App\Http\Controllers\Dashboard\FAQController;
use App\Http\Controllers\Dashboard\MythController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\ServiceProviderController;
use App\Http\Controllers\Site\SiteController;
use Illuminate\Support\Facades\Route;

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
    // analytics
    Route::get('/dashboard', DashboardController::class)->name('admin.dashboard');

    // messages
    Route::get('/chats', [ChatController::class, 'index'])->name('admin.chats');
    Route::post('/chats/{chat:uid}/messages', [ChatController::class, 'message'])->name('admin.chats.messages.store');

    // myths and facts
    Route::resource('myths', MythController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names([
            'index' => 'admin.myths',
            'store' => 'admin.myths.store',
            'update' => 'admin.myths.update',
            'destroy' => 'admin.myths.destroy',
        ]);

    // service providers
    Route::resource('providers', ServiceProviderController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names([
            'index' => 'admin.providers',
            'store' => 'admin.providers.store',
            'update' => 'admin.providers.update',
            'destroy' => 'admin.providers.destroy'
        ]);

    // faqs
    Route::resource('faqs', FAQController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names([
            'index' => 'admin.faqs',
            'store' => 'admin.faqs.store',
            'update' => 'admin.faqs.update',
            'destroy' => 'admin.faqs.destroy'
        ]);
    
    // users
    Route::resource('users', UserController::class)
        ->only('index', 'update', 'destroy')
        ->names([
            'index' => 'admin.users',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy'
        ]);

    // settings
    Route::prefix('settings')->group(function () {
        Route::get('profile', [ProfileController::class, 'index'])->name('admin.settings.profile');
        Route::put('profile/{user}/basic', [ProfileController::class, 'updateBasic'])->name('admin.settings.profile.update.basic');
        Route::put('profile/{user}/security', [ProfileController::class, 'updatePassword'])->name('admin.settings.profile.update.security');
        Route::get('data', [DataManagementController::class, 'index'])->name('admin.settings.data');
        Route::get('data/export/myths', [DataManagementController::class, 'exportMyths'])->name('admin.settings.data.export.myths');
        Route::get('data/export/providers', [DataManagementController::class, 'exportProviders'])->name('admin.settings.data.export.providers');
        Route::get('data/export/faqs', [DataManagementController::class, 'exportFAQs'])->name('admin.settings.data.export.faqs');
        Route::post('data', [DataManagementController::class, 'import'])->name('admin.settings.data.import');
    });
});