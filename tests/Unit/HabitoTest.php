<?php

use App\Models\Habito;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('exposes only the enabled habit frequencies', function () {
    expect(Habito::FREQUENCIES)
        ->toContain('Daria', 'Semanal', 'Mensual', 'Anual')
        ->not->toContain('Pluridiaria');
});

it('returns zero streak when the habit has no completions', function () {
    $habito = Habito::factory()->create();

    expect($habito->getCurrentRacha())->toBe(0);
});

it('returns the streak stored in the latest completion', function () {
    $habito = Habito::factory()->create();

    DB::table('cumplimiento_habito')->insert([
        'Habito_ID' => $habito->ID_Habito,
        'MarcaCumplimiento' => now()->subDay(),
        'RachaAlCumplir' => 4,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('cumplimiento_habito')->insert([
        'Habito_ID' => $habito->ID_Habito,
        'MarcaCumplimiento' => now(),
        'RachaAlCumplir' => 5,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    expect($habito->getCurrentRacha())->toBe(5);
});

it('resets the streak when a failure happens after the latest completion', function () {
    $habito = Habito::factory()->create();

    DB::table('cumplimiento_habito')->insert([
        'Habito_ID' => $habito->ID_Habito,
        'MarcaCumplimiento' => now()->subDay(),
        'RachaAlCumplir' => 8,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('fallo_habito')->insert([
        'Habito_ID' => $habito->ID_Habito,
        'MarcaFallo' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    expect($habito->getCurrentRacha())->toBe(0);
});

it('detects whether the habit was completed today', function () {
    $habito = Habito::factory()->create();

    expect($habito->isCompletedToday())->toBeFalse();

    DB::table('cumplimiento_habito')->insert([
        'Habito_ID' => $habito->ID_Habito,
        'MarcaCumplimiento' => now(),
        'RachaAlCumplir' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    expect($habito->fresh()->isCompletedToday())->toBeTrue();
});

it('resolves progress from preloaded attributes without extra queries', function () {
    $habito = Habito::factory()->create(['Frecuencia' => 'Daria']);

    DB::table('cumplimiento_habito')->insert([
        'Habito_ID' => $habito->ID_Habito,
        'MarcaCumplimiento' => now()->subDay(),
        'RachaAlCumplir' => 6,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $loaded = Habito::withProgress()->findOrFail($habito->ID_Habito);

    DB::enableQueryLog();

    expect($loaded->getCurrentRacha())->toBe(6)
        ->and($loaded->isCompletedToday())->toBeFalse()
        ->and($loaded->isDueToday())->toBeTrue()
        ->and(DB::getQueryLog())->toBeEmpty();

    DB::disableQueryLog();
});

it('builds an annual heatmap with 53 weeks and 7 days per week', function () {
    $habito = Habito::factory()->create();

    DB::table('cumplimiento_habito')->insert([
        'Habito_ID' => $habito->ID_Habito,
        'MarcaCumplimiento' => now(),
        'RachaAlCumplir' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $heatmap = $habito->getAnnualHeatmap();
    $today = collect($heatmap['weeks'])->flatten(1)->firstWhere('today', true);

    expect($heatmap['weeks'])->toHaveCount(53)
        ->and($heatmap['months'])->not->toBeEmpty()
        ->and($today)->not->toBeNull()
        ->and($today['count'])->toBe(1)
        ->and(collect($heatmap['weeks'])->every(fn (array $week): bool => count($week) === 7))->toBeTrue();
});
