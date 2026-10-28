<?php

use App\Models\Habito;
use App\Models\Meta;
use App\Models\Recompensa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('creates a habit for the authenticated user', function () {
    $response = $this->actingAs($this->user)->post(route('habit.create'), [
        'objetivo' => 'Leer 15 minutos',
        'descripcion' => 'Para aprender',
        'frecuencia' => 'Daria',
        'prioridad' => 'Alta',
    ]);

    $response->assertRedirect(route('dashboard'));

    $habito = Habito::where('Usuario_ID', $this->user->ID_Usuario)->first();

    expect($habito)->not->toBeNull()
        ->and($habito->Objetivo)->toBe('Leer 15 minutos')
        ->and($habito->Descripcion)->toBe('Para aprender')
        ->and($habito->Frecuencia)->toBe('Daria')
        ->and($habito->Prioridad)->toBe('Alta');
});

it('rejects pluridiary habits while they are disabled', function () {
    $this->actingAs($this->user)
        ->from(route('dashboard'))
        ->post(route('habit.create'), [
            'objetivo' => 'Beber agua',
            'frecuencia' => 'Pluridiaria',
            'prioridad' => 'Media',
        ])
        ->assertSessionHasErrors('frecuencia');

    expect(Habito::count())->toBe(0);
});

it('updates an owned habit', function () {
    $habito = Habito::factory()->for($this->user, 'usuario')->create([
        'Objetivo' => 'Correr',
        'Frecuencia' => 'Semanal',
        'Prioridad' => 'Baja',
    ]);

    $response = $this->actingAs($this->user)->patch(route('habit.edit'), [
        'habito_id' => $habito->ID_Habito,
        'objetivo' => 'Correr 5 km',
        'descripcion' => 'Los sábados',
        'frecuencia' => 'Mensual',
        'prioridad' => 'Alta',
    ]);

    $response->assertRedirect(route('dashboard'));

    $habito->refresh();

    expect($habito->Objetivo)->toBe('Correr 5 km')
        ->and($habito->Descripcion)->toBe('Los sábados')
        ->and($habito->Frecuencia)->toBe('Mensual')
        ->and($habito->Prioridad)->toBe('Alta');
});

it('rejects updating a habit owned by another user', function () {
    $habito = Habito::factory()->create(['Objetivo' => 'Ajeno']);

    $this->actingAs($this->user)
        ->from(route('dashboard'))
        ->patch(route('habit.edit'), [
            'habito_id' => $habito->ID_Habito,
            'objetivo' => 'Modificado',
            'frecuencia' => 'Daria',
            'prioridad' => 'Media',
        ])
        ->assertSessionHasErrors('habito_id');

    expect($habito->fresh()->Objetivo)->toBe('Ajeno');
});

it('rejects turning a habit into a pluridiary one', function () {
    $habito = Habito::factory()->for($this->user, 'usuario')->create();

    $this->actingAs($this->user)
        ->from(route('dashboard'))
        ->patch(route('habit.edit'), [
            'habito_id' => $habito->ID_Habito,
            'objetivo' => $habito->Objetivo,
            'frecuencia' => 'Pluridiaria',
            'prioridad' => $habito->Prioridad,
        ])
        ->assertSessionHasErrors('frecuencia');

    expect($habito->fresh()->Frecuencia)->not->toBe('Pluridiaria');
});

it('keeps goals, rewards and deliveries synchronized when a habit is updated', function () {
    $habito = Habito::factory()->for($this->user, 'usuario')->create();
    $meta = Meta::factory()->for($habito, 'habito')->create(['RachaRequerida' => 30]);
    $recompensa = Recompensa::factory()->for($this->user, 'usuario')->create();

    $meta->recompensas()->attach($recompensa->ID_Recompensa, ['Habito_ID' => $habito->ID_Habito]);

    DB::table('cumplimiento_meta')->insert([
        'Habito_ID' => $habito->ID_Habito,
        'RachaRequerida' => 30,
        'MarcaCumplimiento' => now(),
        'RachaAlCumplir' => 30,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($this->user)->patch(route('habit.edit'), [
        'habito_id' => $habito->ID_Habito,
        'objetivo' => 'Hábito renovado',
        'frecuencia' => 'Semanal',
        'prioridad' => 'Alta',
    ])->assertRedirect(route('dashboard'));

    expect(Meta::where('Habito_ID', $habito->ID_Habito)->where('RachaRequerida', 30)->exists())->toBeTrue()
        ->and(DB::table('recompensas_meta')->where('Habito_ID', $habito->ID_Habito)->exists())->toBeTrue()
        ->and(DB::table('cumplimiento_meta')->where('Habito_ID', $habito->ID_Habito)->exists())->toBeTrue();
});

it('shows a habit with its linked goals and rewards', function () {
    $habito = Habito::factory()->for($this->user, 'usuario')->create(['Objetivo' => 'Leer']);
    $meta = Meta::factory()->for($habito, 'habito')->create(['RachaRequerida' => 30]);
    $recompensa = Recompensa::factory()->for($this->user, 'usuario')->create(['Nombre' => 'Café especial']);

    $meta->recompensas()->attach($recompensa->ID_Recompensa, ['Habito_ID' => $habito->ID_Habito]);

    $this->actingAs($this->user)
        ->get(route('habit', $habito->ID_Habito))
        ->assertOk()
        ->assertSee('Leer')
        ->assertSee('30 días seguidos')
        ->assertSee('Café especial');
});

it('deletes an owned habit with its goals, rewards links, deliveries and history', function () {
    $habito = Habito::factory()->for($this->user, 'usuario')->create();
    $meta = Meta::factory()->for($habito, 'habito')->create(['RachaRequerida' => 15]);
    $recompensa = Recompensa::factory()->for($this->user, 'usuario')->create();

    $meta->recompensas()->attach($recompensa->ID_Recompensa, ['Habito_ID' => $habito->ID_Habito]);

    DB::table('cumplimiento_meta')->insert([
        'Habito_ID' => $habito->ID_Habito,
        'RachaRequerida' => 15,
        'MarcaCumplimiento' => now(),
        'RachaAlCumplir' => 15,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::table('cumplimiento_habito')->insert([
        'Habito_ID' => $habito->ID_Habito,
        'MarcaCumplimiento' => now(),
        'RachaAlCumplir' => 2,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::table('fallo_habito')->insert([
        'Habito_ID' => $habito->ID_Habito,
        'MarcaFallo' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::table('recordatorio')->insert([
        'Habito_ID' => $habito->ID_Habito,
        'ProximaFecha' => now()->addDay(),
        'Mensaje' => 'No olvidar',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($this->user)
        ->delete(route('habit.delete', $habito))
        ->assertRedirect(route('dashboard'));

    expect(Habito::find($habito->ID_Habito))->toBeNull()
        ->and(Meta::where('Habito_ID', $habito->ID_Habito)->exists())->toBeFalse()
        ->and(DB::table('recompensas_meta')->where('Habito_ID', $habito->ID_Habito)->exists())->toBeFalse()
        ->and(DB::table('cumplimiento_meta')->where('Habito_ID', $habito->ID_Habito)->exists())->toBeFalse()
        ->and(DB::table('cumplimiento_habito')->where('Habito_ID', $habito->ID_Habito)->exists())->toBeFalse()
        ->and(DB::table('fallo_habito')->where('Habito_ID', $habito->ID_Habito)->exists())->toBeFalse()
        ->and(DB::table('recordatorio')->where('Habito_ID', $habito->ID_Habito)->exists())->toBeFalse()
        ->and(Recompensa::find($recompensa->ID_Recompensa))->not->toBeNull();
});

it('rejects deleting a habit owned by another user', function () {
    $habito = Habito::factory()->create();

    $this->actingAs($this->user)
        ->from(route('dashboard'))
        ->delete(route('habit.delete'), ['habito_id' => $habito->ID_Habito])
        ->assertSessionHasErrors('habito_id');

    expect(Habito::find($habito->ID_Habito))->not->toBeNull();
});

it('supports the full habit, goal and reward user flow', function () {
    $this->actingAs($this->user)->post(route('habit.create'), [
        'objetivo' => 'Meditar',
        'frecuencia' => 'Daria',
        'prioridad' => 'Media',
    ])->assertRedirect(route('dashboard'));

    $habito = Habito::where('Usuario_ID', $this->user->ID_Usuario)->firstOrFail();

    $this->actingAs($this->user)->post(route('reward.create'), [
        'nombre' => 'Té especial',
        'emoji' => '☕',
    ])->assertRedirect(route('goals'));

    $recompensa = Recompensa::where('Usuario_ID', $this->user->ID_Usuario)->firstOrFail();

    $this->actingAs($this->user)->post(route('goals.create'), [
        'habito_id' => $habito->ID_Habito,
        'racha_requerida' => 21,
        'repetible' => 1,
        'recompensa_id' => $recompensa->ID_Recompensa,
    ])->assertRedirect(route('goals'));

    $this->actingAs($this->user)->patch(route('habit.edit', $habito), [
        'objetivo' => 'Meditar 10 minutos',
        'frecuencia' => 'Daria',
        'prioridad' => 'Alta',
    ])->assertRedirect(route('dashboard'));

    $this->actingAs($this->user)
        ->get(route('goals'))
        ->assertOk()
        ->assertSee('Meditar 10 minutos')
        ->assertSee('Té especial');

    $this->actingAs($this->user)
        ->delete(route('habit.delete', $habito))
        ->assertRedirect(route('dashboard'));

    expect(Habito::find($habito->ID_Habito))->toBeNull()
        ->and(Meta::where('Habito_ID', $habito->ID_Habito)->exists())->toBeFalse()
        ->and(Recompensa::find($recompensa->ID_Recompensa))->not->toBeNull();

});
