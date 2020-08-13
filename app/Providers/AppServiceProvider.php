<?php

namespace App\Providers;

use App\Builder\IJsonResponseBuilder;
use App\Builder\JsonResponseBuilder;
use App\Service\Mail\IMailService;
use App\Service\Mail\MailService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IMailService::class, MailService::class);
        $this->app->bind(IJsonResponseBuilder::class, JsonResponseBuilder::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
