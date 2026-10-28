<?php

namespace App\Services\WhatsApp;

use App\Contracts\WhatsAppGateway;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WahaWhatsAppGateway implements WhatsAppGateway
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $apiKey,
        private readonly string $session,
    ) {}

    public function send(string $phone, string $message): void
    {
        if ($this->apiKey === '') {
            throw new WhatsAppException('WAHA_API_KEY no está configurada.');
        }

        try {
            $chatId = $this->resolveChatId($phone);

            $response = Http::acceptJson()
                ->withHeaders(['X-Api-Key' => $this->apiKey])
                ->timeout(20)
                ->post($this->url('/api/sendText'), [
                    'session' => $this->session,
                    'chatId' => $chatId,
                    'text' => $message,
                ]);
        } catch (ConnectionException $exception) {
            throw new WhatsAppException('No se pudo conectar con WAHA.', previous: $exception);
        }

        if ($response->failed()) {
            throw new WhatsAppException(
                'WAHA respondió con error '.$response->status().': '.$this->errorDetail($response->json('message') ?? $response->body()),
            );
        }
    }

    private function resolveChatId(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        if (! $digits) {
            throw new WhatsAppException('El teléfono no contiene un número válido.');
        }

        $response = Http::acceptJson()
            ->withHeaders(['X-Api-Key' => $this->apiKey])
            ->timeout(20)
            ->get($this->url('/api/contacts/check-exists'), [
                'session' => $this->session,
                'phone' => $digits,
            ]);

        if ($response->failed()) {
            throw new WhatsAppException(
                'WAHA respondió con error '.$response->status().': '.$this->errorDetail($response->json('message') ?? $response->body()),
            );
        }

        if (! $response->json('numberExists')) {
            throw new WhatsAppException('El número no está registrado en WhatsApp.');
        }

        $chatId = $response->json('chatId');

        return is_string($chatId) && $chatId !== '' ? $chatId : $digits.'@c.us';
    }

    private function url(string $path): string
    {
        return rtrim($this->baseUrl, '/').$path;
    }

    private function errorDetail(mixed $detail): string
    {
        return Str::limit(is_string($detail) ? $detail : json_encode($detail), 500);
    }
}
