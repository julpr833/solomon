<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('persists the biography when updated through the endpoint', function () {
    $user = User::create([
        'NombreUsuario' => 'biotester',
        'email' => 'bio@example.com',
        'password' => Hash::make('password'),
        'Avatar_URL' => 'https://example.com/a.png',
        'Sexo' => 'M',
    ]);

    $response = $this->actingAs($user)->patch(route('settings.biography'), [
        'biografia' => 'Me gusta correr',
    ]);

    $response->assertOk();
    expect($user->fresh()->Biografia)->toBe('Me gusta correr');
});
