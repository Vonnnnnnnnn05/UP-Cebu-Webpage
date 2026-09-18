<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Portal Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');
Route::get('/api/search', [HomeController::class, 'search'])->name('api.search');
Route::get('/news/{slug}', [HomeController::class, 'article'])->name('news.article');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    /*
    |--------------------------------------------------------------------------
    | Protected Admin Routes (Requires 'admin' guard)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // News CRUD & Toggle
        Route::post('/news/{news}/toggle-featured', [AdminNewsController::class, 'toggleFeatured'])->name('admin.news.toggle-featured');
        Route::resource('news', AdminNewsController::class)->names('admin.news');

        // Events CRUD & Toggle
        Route::post('/events/{event}/toggle-featured', [AdminEventController::class, 'toggleFeatured'])->name('admin.events.toggle-featured');
        Route::resource('events', AdminEventController::class)->names('admin.events');

        // Inquiries Management
        Route::resource('inquiries', AdminInquiryController::class)
            ->only(['index', 'show', 'update', 'destroy'])
            ->names('admin.inquiries');
    });
});
