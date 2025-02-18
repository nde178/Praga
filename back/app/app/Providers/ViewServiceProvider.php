<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Композер для всех представлений, использующих сайтбар
        View::composer('layouts.sidebar', function ($view) {
            $categories = Category::all();
            $view->with('categories', $categories);
        });
    }
}
