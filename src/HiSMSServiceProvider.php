<?php

namespace ITBrains\HiSMS;

use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use Log;

class HiSMSServiceProvider extends ServiceProvider
{
    public const API_DRIVER = 'hisms';

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(HiSMSClient::class, function () {
            if (config('hi-sms.driver') === self::API_DRIVER) {
                return new Client();
            }

            if ($this->app->isProduction()) {
                Log::warning("You shouldn't use faker Hi SMS client on the production mode.");
            }

            return new FakedClient();
        });

        $this->mergeConfigFrom(__DIR__ . '/../config/hi-sms.php', 'hi-sms');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [HiSMSClient::class];
    }

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'hi_sms');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/hi_sms'),
        ]);
    }
}
