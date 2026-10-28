<?php

namespace App\Providers;

use App\Contracts\WhatsAppGateway;
use App\Services\WhatsApp\LogWhatsAppGateway;
use App\Services\WhatsApp\WahaWhatsAppGateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(WhatsAppGateway::class, function (): WhatsAppGateway {
            if (config('services.whatsapp.driver') !== 'waha') {
                return new LogWhatsAppGateway;
            }

            return new WahaWhatsAppGateway(
                (string) config('services.whatsapp.waha.base_url'),
                (string) config('services.whatsapp.waha.api_key'),
                (string) config('services.whatsapp.waha.session'),
            );
        });
    }

    public function boot(): void
    {
        //
    }
}
