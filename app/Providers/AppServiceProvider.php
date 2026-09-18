<?php

namespace App\Providers;

use App\Models\Inquiry;
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
        });
    }
}
