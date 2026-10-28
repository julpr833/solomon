<?php

use App\Services\WhatsApp\WahaWhatsAppGateway;
use App\Services\WhatsApp\WhatsAppException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class);

it('resolves the WhatsApp LID and sends a text message through the WAHA API', function () {
    Http::fake([
        'http://waha.test/api/contacts/check-exists*' => Http::response([
            'numberExists' => true,
            'chatId' => '98093362090023@lid',
        ]),
        'http://waha.test/api/sendText' => Http::response(['id' => 'message-id'], 201),
    ]);

    $gateway = new WahaWhatsAppGateway('http://waha.test', 'secret-key', 'default');

    $gateway->send('+54 9 11 2345-6789', 'Hola');

    Http::assertSent(function (Request $request): bool {
        return str_starts_with($request->url(), 'http://waha.test/api/contacts/check-exists')
            && $request['session'] === 'default'
            && $request['phone'] === '5491123456789';
    });

    Http::assertSent(function (Request $request): bool {
        return $request->url() === 'http://waha.test/api/sendText'
            && $request->hasHeader('X-Api-Key', 'secret-key')
            && $request['session'] === 'default'
            && $request['chatId'] === '98093362090023@lid'
            && $request['text'] === 'Hola';
    });
});

it('does not send when the phone is not registered in WhatsApp', function () {
    Http::fake([
        'http://waha.test/api/contacts/check-exists*' => Http::response([
            'numberExists' => false,
        ]),
        'http://waha.test/api/sendText' => Http::response([], 201),
    ]);

    $gateway = new WahaWhatsAppGateway('http://waha.test', 'secret-key', 'default');

    expect(fn () => $gateway->send('+549113765371346', 'Hola'))
        ->toThrow(WhatsAppException::class, 'no está registrado');

    Http::assertNotSent(fn (Request $request): bool => $request->url() === 'http://waha.test/api/sendText');
});

it('throws a clear exception when WAHA responds with an error', function () {
    Http::fake([
        'http://waha.test/api/contacts/check-exists*' => Http::response([
            'numberExists' => true,
            'chatId' => '98093362090023@lid',
        ]),
        'http://waha.test/api/sendText' => Http::response(['message' => 'Session not working'], 500),
    ]);

    $gateway = new WahaWhatsAppGateway('http://waha.test', 'secret-key', 'default');

    expect(fn () => $gateway->send('+5491123456789', 'Hola'))
        ->toThrow(WhatsAppException::class, 'Session not working');
});

it('requires a configured WAHA API key', function () {
    $gateway = new WahaWhatsAppGateway('http://waha.test', '', 'default');

    expect(fn () => $gateway->send('+5491123456789', 'Hola'))
        ->toThrow(WhatsAppException::class, 'WAHA_API_KEY');
});
