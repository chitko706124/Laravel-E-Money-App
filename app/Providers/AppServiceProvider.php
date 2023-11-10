<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View as allView;
use Illuminate\View\View;




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
        Paginator::useBootstrapFive();


        // View::share('unread_noti_count', $unread_noti_count);

        allView::composer('*', function (View $view) {
            $unread_noti_count = 0;
            if (auth()->guard('web')->check()) {
                $unread_noti_count = auth()->guard('web')->user()->unreadNotifications()->count();
            }

            $view->with('unread_noti_count', $unread_noti_count);
        });
    }
}
