<?php

use App\Models\Habito;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function makeHabitsUser(): User
{
    return User::create([
        'NombreUsuario' => 'habitsuser',
        'email' => 'habits@example.com',
        'password' => Hash::make('password'),
        'Avatar_URL' => 'https://example.com/a.png',
        'Sexo' => 'M',
    ]);
}

it('creates a habit through the controller', function () {
    $user = makeHabitsUser();

    $response = $this->actingAs($user)->post(route('habit.create'), [
        'objetivo' => 'Leer 15 minutos',
        'descripcion' => 'Para aprender',
        'frecuencia' => 'Daria',
        'prioridad' => 'Alta',
    ]);

    $response->assertRedirect(route('dashboard'));

    $habito = Habito::where('Usuario_ID', $user->ID_Usuario)->first();
    expect($habito)->not->toBeNull();
    expect($habito->Objetivo)->toBe('Leer 15 minutos');
    expect($habito->Frecuencia)->toBe('Daria');
});

it('rejects an invalid frequency', function () {
    $user = makeHabitsUser();

    $this->actingAs($user)
        ->from(route('dashboard'))
        ->post(route('habit.create'), [
            'objetivo' => 'Correr',
            'frecuencia' => 'Cada_2_dias',
            'prioridad' => 'Media',
        ])
        ->assertSessionHasErrors('frecuencia');

    expect(Habito::count())->toBe(0);
});

it('requires a goal name', function () {
    $user = makeHabitsUser();

    $this->actingAs($user)
        ->from(route('dashboard'))
        ->post(route('habit.create'), [
            'frecuencia' => 'Daria',
            'prioridad' => 'Media',
        ])
        ->assertSessionHasErrors('objetivo');
});
