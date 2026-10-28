<?php

namespace App\Services\WhatsApp;

use App\Contracts\WhatsAppGateway;
use Illuminate\Support\Facades\Log;

class LogWhatsAppGateway implements WhatsAppGateway
{
    public function send(string $phone, string $message): void
    {
        Log::info('WhatsApp message (log driver)', [
            'to' => $phone,
            'message' => $message,
        ]);
    }
}
