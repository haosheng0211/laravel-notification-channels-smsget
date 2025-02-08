<?php

namespace NotificationChannels\SmsGet;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\ServiceProvider;

class SmsGetServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(SmsGetChannel::class)
            ->needs(SmsGet::class)
            ->give(function () {
                $config = config('services.smsget');

                return new SmsGet(new GuzzleClient(), $config['username'], $config['password']);
            });
    }

    /**
     * Register the application services.
     */
    public function register(): array
    {
        return [SmsGet::class];
    }
}
