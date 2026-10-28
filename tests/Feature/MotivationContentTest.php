<?php

use App\Models\FraseMotivacional;
use App\Models\Proverbio;
use App\Models\User;
use Database\Seeders\FraseMotivacionalSeeder;
use Database\Seeders\ProverbioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('seeds proverbs idempotently', function () {
    $this->seed(ProverbioSeeder::class);

    $count = Proverbio::count();

    $this->seed(ProverbioSeeder::class);

    expect($count)->toBeGreaterThan(0)
        ->and(Proverbio::count())->toBe($count)
        ->and(Proverbio::where('Capitulo', 15)->where('Versiculo', 1)->exists())->toBeTrue();
});

it('seeds motivational quotes idempotently', function () {
    $this->seed(FraseMotivacionalSeeder::class);

    $count = FraseMotivacional::count();

    $this->seed(FraseMotivacionalSeeder::class);

    expect($count)->toBeGreaterThan(0)
        ->and(FraseMotivacional::count())->toBe($count)
        ->and(FraseMotivacional::where('Fuente', 'Steve Jobs')->exists())->toBeTrue();
});

it('renders motivational content from the database', function () {
    $this->seed([ProverbioSeeder::class, FraseMotivacionalSeeder::class]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Steve Jobs', false)
        ->assertSee('Proverbios 15:1', false);
});

it('does not load motivational content when disabled', function () {
    $this->seed([ProverbioSeeder::class, FraseMotivacionalSeeder::class]);
    $user = User::factory()->create();
    $user->preferencias()->create(['ContenidoMotivacional' => 'Ninguno']);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Contenido motivacional desactivado', false)
        ->assertDontSee('Steve Jobs', false);
});
