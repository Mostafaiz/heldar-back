<?php

namespace App\Providers;

use App\Services\AttributeService;
use App\Services\AuthenticationService;
use App\Services\CategoryService;
use App\Services\ColorService;
use App\Services\GalleryService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('ColorService', function () {
            return new ColorService();
        });
        $this->app->singleton('AttributeService', function () {
            return new AttributeService();
        });
        $this->app->singleton('AuthService', function () {
            return new AuthenticationService();
        });
        $this->app->singleton('CategoryService', function () {
            return new CategoryService();
        });
        $this->app->singleton('GalleryService', function () {
            return new GalleryService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
