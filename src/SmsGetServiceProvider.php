<?php

namespace NotificationChannels\SmsGet;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class SmsGetServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->singleton(SmsGet::class, function ($app) {
            $username = $app['config']['services.sms-get.username'];
            $password = $app['config']['services.sms-get.username'];

            if (empty($username) || empty($password)) {
                throw new \InvalidArgumentException('Missing SmsGet config in services');
            }

            return new SmsGet(new GuzzleClient(), $username, $password);
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('sms-get', function ($app) {
                return new SmsGetChannel($app->make(SmsGet::class));
            });
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
