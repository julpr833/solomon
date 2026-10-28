<?php

use App\Contracts\WhatsAppGateway;
use App\Models\Habito;
use App\Models\User;
use App\Services\WhatsApp\WhatsAppException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('sends a test WhatsApp message to the saved user phone', function () {
    $user = User::factory()->create(['telefono' => '+5491123456789']);
    Habito::factory()->count(2)->for($user, 'usuario')->create();

    $this->mock(WhatsAppGateway::class, function (MockInterface $mock): void {
        $mock->shouldReceive('send')
            ->once()
            ->withArgs(fn (string $phone, string $message): bool => $phone === '+5491123456789'
                && str_contains($message, 'Prueba de recordatorios')
                && str_contains($message, 'Hábitos configurados: 2'));
    });

    $this->actingAs($user)
        ->postJson(route('settings.whatsapp.test'))
        ->assertOk()
        ->assertJson(['message' => 'Mensaje de prueba enviado a +5491123456789.']);
});

it('renders the WhatsApp test button in settings', function () {
    $user = User::factory()->create(['telefono' => '+5491123456789']);

    $this->actingAs($user)
        ->get(route('settings'))
        ->assertOk()
        ->assertSee('Probar envío');
});

it('requires a phone before sending the test message', function () {
    $user = User::factory()->create(['telefono' => null]);

    $this->mock(WhatsAppGateway::class, function (MockInterface $mock): void {
        $mock->shouldNotReceive('send');
    });

    $this->actingAs($user)
        ->postJson(route('settings.whatsapp.test'))
        ->assertUnprocessable()
        ->assertJson(['message' => 'Configurá un número de WhatsApp antes de probar.']);
});

it('returns the gateway error when the test message fails', function () {
    $user = User::factory()->create(['telefono' => '+5491123456789']);

    $this->mock(WhatsAppGateway::class, function (MockInterface $mock): void {
        $mock->shouldReceive('send')->once()->andThrow(new WhatsAppException('WAHA no disponible'));
    });

    $this->actingAs($user)
        ->postJson(route('settings.whatsapp.test'))
        ->assertStatus(502)
        ->assertJson(['message' => 'No se pudo enviar la prueba: WAHA no disponible']);
});
