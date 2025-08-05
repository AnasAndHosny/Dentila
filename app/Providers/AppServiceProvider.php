<?php

namespace App\Providers;

use App\Channels\WhatsAppChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;

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
        Model::automaticallyEagerLoadRelationships();

        app(ChannelManager::class)->extend('whatsapp', function () {
            return new WhatsAppChannel;
        });
    }
}
