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
Route::get('/events/{slug}', [HomeController::class, 'eventDetail'])->name('events.show');


Route::redirect('/login', '/admin/login');

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

        // Events CRUD & Toggle & Daily Details & Approvals
        Route::post('/events/{event}/toggle-featured', [AdminEventController::class, 'toggleFeatured'])->name('admin.events.toggle-featured');
        Route::post('/events/{event}/approve', [AdminEventController::class, 'approve'])->name('admin.events.approve');
        Route::post('/events/{event}/review', [AdminEventController::class, 'review'])->name('admin.events.review');
        Route::post('/events/{event}/details', [AdminEventController::class, 'storeDetail'])->name('admin.events.details.store');
        Route::put('/events/{event}/details/{detail}', [AdminEventController::class, 'updateDetail'])->name('admin.events.details.update');
        Route::delete('/events/{event}/details/{detail}', [AdminEventController::class, 'destroyDetail'])->name('admin.events.details.destroy');
        Route::resource('events', AdminEventController::class)->names('admin.events');

        // Inquiries Management & Approvals
        Route::post('/inquiries/{inquiry}/approve', [AdminInquiryController::class, 'approve'])->name('admin.inquiries.approve');
        Route::post('/inquiries/{inquiry}/decline', [AdminInquiryController::class, 'decline'])->name('admin.inquiries.decline');
        Route::post('/inquiries/{inquiry}/review', [AdminInquiryController::class, 'review'])->name('admin.inquiries.review');
        Route::resource('inquiries', AdminInquiryController::class)
            ->only(['index', 'show', 'update', 'destroy'])
            ->names('admin.inquiries');
    });
});
