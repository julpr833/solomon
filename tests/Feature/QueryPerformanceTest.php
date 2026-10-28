<?php

use App\Models\Habito;
use App\Models\Meta;
use App\Models\Recompensa;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

/**
 * @return Collection<int, Habito>
 */
function createHabitsWithHistory(User $user, int $count = 4): Collection
{
    $habits = Habito::factory()->count($count)->for($user, 'usuario')->create();
    $reward = Recompensa::factory()->for($user, 'usuario')->create();

    foreach ($habits as $index => $habit) {
        DB::table('cumplimiento_habito')->insert([
            'Habito_ID' => $habit->ID_Habito,
            'MarcaCumplimiento' => now()->subDay()->addSeconds($index),
            'RachaAlCumplir' => $index + 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('fallo_habito')->insert([
            'Habito_ID' => $habit->ID_Habito,
            'MarcaFallo' => now()->subDays(2)->addSeconds($index),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $meta = Meta::factory()->for($habit, 'habito')->create([
            'RachaRequerida' => 10 + $index,
        ]);
        $meta->recompensas()->attach($reward->ID_Recompensa, ['Habito_ID' => $habit->ID_Habito]);
    }

    return $habits;
}

it('loads the dashboard without per-habit queries', function () {
    $user = User::factory()->create();
    createHabitsWithHistory($user);

    DB::enableQueryLog();
    $response = $this->actingAs($user)->get(route('dashboard'));
    $queries = DB::getQueryLog();
    DB::disableQueryLog();

    $response->assertOk();

    expect($queries)->toHaveCount(7);
});

it('loads goals without per-goal or per-habit queries', function () {
    $user = User::factory()->create();
    createHabitsWithHistory($user);

    DB::enableQueryLog();
    $response = $this->actingAs($user)->get(route('goals'));
    $queries = DB::getQueryLog();
    DB::disableQueryLog();

    $response->assertOk();

    expect($queries)->toHaveCount(5);
});

it('loads a habit detail with constant queries', function () {
    $user = User::factory()->create();
    $habit = createHabitsWithHistory($user)->first();

    DB::enableQueryLog();
    $response = $this->actingAs($user)->get(route('habit', $habit->ID_Habito));
    $queries = DB::getQueryLog();
    DB::disableQueryLog();

    $response->assertOk();

    expect($queries)->toHaveCount(5);
});
