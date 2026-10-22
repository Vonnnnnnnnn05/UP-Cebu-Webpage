<?php

namespace App\Providers;

use App\Models\Inquiry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('admin.*', function ($view) {
            $pendingCount = 0;
            if (Schema::hasTable('inquiries')) {
                $pendingCount = Inquiry::pending()->count();
            }
            $view->with('pending_inquiries_count', $pendingCount);

            $unreadNotificationsCount = 0;
            $recentNotifications = collect();

            if (Auth::guard('admin')->check() && Schema::hasTable('notifications')) {
                $adminUser = Auth::guard('admin')->user();
                $unreadNotificationsCount = $adminUser->unreadNotifications()->count();
                $recentNotifications = $adminUser->notifications()->take(6)->get();
            }

            $view->with('unread_notifications_count', $unreadNotificationsCount);
            $view->with('recent_notifications', $recentNotifications);
        });
    }
}
