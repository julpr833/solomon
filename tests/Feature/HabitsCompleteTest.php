<?php

use App\Models\Habito;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('marks an owned habit as completed today', function () {
    $user = User::factory()->create();
    $habito = Habito::factory()->for($user, 'usuario')->create();

    $this->actingAs($user)
        ->postJson(route('habit.complete', $habito->ID_Habito))
        ->assertOk()
        ->assertJson([
            'completed' => true,
            'streak' => 1,
            'already_completed' => false,
        ]);

    expect(DB::table('cumplimiento_habito')->where('Habito_ID', $habito->ID_Habito)->count())->toBe(1);
});

it('renders the completed state after navigating back to the dashboard', function () {
    $user = User::factory()->create();
    $habito = Habito::factory()->for($user, 'usuario')->create();

    $this->actingAs($user)->postJson(route('habit.complete', $habito->ID_Habito))->assertOk();

    $response = $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Hábito completado hoy');

    expect($response->headers->get('Cache-Control'))
        ->toContain('no-store')
        ->toContain('no-cache')
        ->toContain('must-revalidate');
});

it('does not duplicate the completion when clicked twice on the same day', function () {
    $user = User::factory()->create();
    $habito = Habito::factory()->for($user, 'usuario')->create();

    $this->actingAs($user)->postJson(route('habit.complete', $habito->ID_Habito))->assertOk();

    $this->actingAs($user)
        ->postJson(route('habit.complete', $habito->ID_Habito))
        ->assertOk()
        ->assertJson([
            'completed' => true,
            'streak' => 1,
            'already_completed' => true,
        ]);

    expect(DB::table('cumplimiento_habito')->where('Habito_ID', $habito->ID_Habito)->count())->toBe(1);
});

it('increments the current streak when completing a habit', function () {
    $user = User::factory()->create();
    $habito = Habito::factory()->for($user, 'usuario')->create();

    DB::table('cumplimiento_habito')->insert([
        'Habito_ID' => $habito->ID_Habito,
        'MarcaCumplimiento' => now()->subDay(),
        'RachaAlCumplir' => 3,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($user)
        ->postJson(route('habit.complete', $habito->ID_Habito))
        ->assertOk()
        ->assertJson([
            'streak' => 4,
            'already_completed' => false,
        ]);
});

it('restarts the streak after a failure', function () {
    $user = User::factory()->create();
    $habito = Habito::factory()->for($user, 'usuario')->create();

    DB::table('cumplimiento_habito')->insert([
        'Habito_ID' => $habito->ID_Habito,
        'MarcaCumplimiento' => now()->subDays(2),
        'RachaAlCumplir' => 8,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::table('fallo_habito')->insert([
        'Habito_ID' => $habito->ID_Habito,
        'MarcaFallo' => now()->subDay(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($user)
        ->postJson(route('habit.complete', $habito->ID_Habito))
        ->assertOk()
        ->assertJson([
            'streak' => 1,
            'already_completed' => false,
        ]);
});

it('cannot complete a habit owned by another user', function () {
    $user = User::factory()->create();
    $habito = Habito::factory()->create();

    $this->actingAs($user)
        ->postJson(route('habit.complete', $habito->ID_Habito))
        ->assertNotFound();

    expect(DB::table('cumplimiento_habito')->where('Habito_ID', $habito->ID_Habito)->exists())->toBeFalse();
});

it('unmarks a habit completed today', function () {
    $user = User::factory()->create();
    $habito = Habito::factory()->for($user, 'usuario')->create();

    $this->actingAs($user)->postJson(route('habit.complete', $habito->ID_Habito))->assertOk();

    $this->actingAs($user)
        ->deleteJson(route('habit.uncomplete', $habito->ID_Habito))
        ->assertOk()
        ->assertJson([
            'completed' => false,
            'removed' => true,
            'changed' => true,
            'streak' => 0,
        ]);

    expect(DB::table('cumplimiento_habito')->where('Habito_ID', $habito->ID_Habito)->count())->toBe(0);
});

it('restores the previous streak when unmarking a habit', function () {
    $user = User::factory()->create();
    $habito = Habito::factory()->for($user, 'usuario')->create();

    DB::table('cumplimiento_habito')->insert([
        'Habito_ID' => $habito->ID_Habito,
        'MarcaCumplimiento' => now()->subDay(),
        'RachaAlCumplir' => 3,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($user)->postJson(route('habit.complete', $habito->ID_Habito))->assertOk();

    $this->actingAs($user)
        ->deleteJson(route('habit.uncomplete', $habito->ID_Habito))
        ->assertOk()
        ->assertJson([
            'removed' => true,
            'streak' => 3,
        ]);
});

it('does nothing when unmarking a habit without completion today', function () {
    $user = User::factory()->create();
    $habito = Habito::factory()->for($user, 'usuario')->create();

    $this->actingAs($user)
        ->deleteJson(route('habit.uncomplete', $habito->ID_Habito))
        ->assertOk()
        ->assertJson([
            'removed' => false,
            'changed' => false,
            'streak' => 0,
        ]);
});

it('cannot uncomplete a habit owned by another user', function () {
    $user = User::factory()->create();
    $habito = Habito::factory()->create();

    $this->actingAs($user)
        ->deleteJson(route('habit.uncomplete', $habito->ID_Habito))
        ->assertNotFound();
});
