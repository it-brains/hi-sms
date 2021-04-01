<?php

namespace ITBrains\HiSMS;

use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use ITBrains\HiSMS\Drivers\ApiClient;
use ITBrains\HiSMS\Drivers\FakedClient;
use ITBrains\HiSMS\Drivers\LogClient;

class HiSMSServiceProvider extends ServiceProvider
{
    public const API_DRIVER = 'hisms';
    public const LOG_DRIVER = 'log';
    public const NULL_DRIVER = 'null';

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(HiSMSClient::class, function () {
            switch ($driver = config('hi-sms.driver')) {
                case self::API_DRIVER:
                    return new ApiClient();
                case self::LOG_DRIVER:
                    return new LogClient();
                case self::NULL_DRIVER:
                    return new FakedClient();
                default:
                    throw new InvalidArgumentException("Unsupported HiSMS '{$driver}' driver.");
            }
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
