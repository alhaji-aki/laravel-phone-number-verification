<?php

namespace AlhajiAki\PhoneNumberVerification;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class PhoneNumberVerificationServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        // Controllers...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/App/Http/Controllers/Auth', app_path('Http/Controllers/Auth'));
    }
}
