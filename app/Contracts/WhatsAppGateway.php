<?php

namespace App\Contracts;

interface WhatsAppGateway
{
    public function send(string $phone, string $message): void;
}
