<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('persists a normalized WhatsApp phone', function () {
    $user = User::factory()->create(['telefono' => null]);

    $this->actingAs($user)
        ->patchJson(route('settings.phone'), ['telefono' => '+54 9 11 2345-6789'])
        ->assertOk()
        ->assertJson(['telefono' => '+5491123456789']);

    expect($user->fresh()->telefono)->toBe('+5491123456789');
});

it('rejects an invalid WhatsApp phone', function () {
    $user = User::factory()->create(['telefono' => null]);

    $this->actingAs($user)
        ->patchJson(route('settings.phone'), ['telefono' => '123'])
        ->assertUnprocessable();

    expect($user->fresh()->telefono)->toBeNull();
});

it('allows clearing the WhatsApp phone', function () {
    $user = User::factory()->create(['telefono' => '+5491123456789']);

    $this->actingAs($user)
        ->patchJson(route('settings.phone'), ['telefono' => ''])
        ->assertOk()
        ->assertJson(['telefono' => null]);

    expect($user->fresh()->telefono)->toBeNull();
});
