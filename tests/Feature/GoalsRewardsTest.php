<?php

use App\Models\Habito;
use App\Models\Meta;
use App\Models\Recompensa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function makeGoalsUser(): User
{
    return User::create([
        'NombreUsuario' => 'tester',
        'email' => 'tester@example.com',
        'password' => Hash::make('password'),
        'Avatar_URL' => 'https://example.com/a.png',
        'Sexo' => 'M',
    ]);
}

it('renders the emoji picker in the reward forms', function () {
    $user = makeGoalsUser();

    $this->actingAs($user)
        ->get(route('goals'))
        ->assertOk()
        ->assertSee('<emoji-picker', false)
        ->assertSee('data-emoji-value', false)
        ->assertDontSee('<select id="remoji"', false);
});

it('creates a reward through the controller', function () {
    $user = makeGoalsUser();

    $response = $this->actingAs($user)->post(route('reward.create'), [
        'nombre' => 'Helado',
        'descripcion' => 'Rico',
        'emoji' => '🍦',
    ]);

    $response->assertRedirect(route('goals'));
    expect(Recompensa::where('Usuario_ID', $user->ID_Usuario)->where('Nombre', 'Helado')->exists())->toBeTrue();
});

it('creates a goal linked to a habit and reward', function () {
    $user = makeGoalsUser();
    $habito = Habito::forceCreate([
        'Usuario_ID' => $user->ID_Usuario,
        'Objetivo' => 'Leer',
        'Prioridad' => 'Media',
        'Frecuencia' => 'Daria',
    ]);
    $recompensa = Recompensa::create([
        'Usuario_ID' => $user->ID_Usuario,
        'Nombre' => 'Café',
        'Emoji' => '☕',
    ]);

    $response = $this->actingAs($user)->post(route('goals.create'), [
        'habito_id' => $habito->ID_Habito,
        'racha_requerida' => 30,
        'repetible' => 1,
        'recompensa_id' => $recompensa->ID_Recompensa,
    ]);

    $response->assertRedirect(route('goals'));
    $meta = Meta::find(30);
    expect($meta)->not->toBeNull();
    expect($meta->Habito_ID)->toBe($habito->ID_Habito);
    expect($meta->recompensas->pluck('ID_Recompensa')->all())->toBe([$recompensa->ID_Recompensa]);
});

it('renders the goals page with data', function () {
    $user = makeGoalsUser();
    Habito::forceCreate([
        'Usuario_ID' => $user->ID_Usuario,
        'Objetivo' => 'Agua',
        'Prioridad' => 'Alta',
        'Frecuencia' => 'Daria',
    ]);

    $this->actingAs($user)->get(route('goals'))->assertOk()->assertSee('Agua');
});

it('deletes a goal', function () {
    $user = makeGoalsUser();
    $habito = Habito::forceCreate([
        'Usuario_ID' => $user->ID_Usuario,
        'Objetivo' => 'Correr',
        'Prioridad' => 'Baja',
        'Frecuencia' => 'Semanal',
    ]);
    Meta::create([
        'Habito_ID' => $habito->ID_Habito,
        'RachaRequerida' => 7,
        'Repetible' => true,
    ]);

    $response = $this->actingAs($user)->delete(route('goals.delete'), [
        'habito_id' => $habito->ID_Habito,
        'racha_requerida' => 7,
    ]);

    $response->assertRedirect(route('goals'));
    expect(Meta::find(7))->toBeNull();
});

it('edits a goal and swaps its reward', function () {
    $user = makeGoalsUser();
    $habito = Habito::forceCreate([
        'Usuario_ID' => $user->ID_Usuario,
        'Objetivo' => 'Estudiar',
        'Prioridad' => 'Media',
        'Frecuencia' => 'Daria',
    ]);
    $meta = Meta::create([
        'Habito_ID' => $habito->ID_Habito,
        'RachaRequerida' => 14,
        'Repetible' => true,
    ]);
    $oldReward = Recompensa::create([
        'Usuario_ID' => $user->ID_Usuario,
        'Nombre' => 'Café',
        'Emoji' => '☕',
    ]);
    $newReward = Recompensa::create([
        'Usuario_ID' => $user->ID_Usuario,
        'Nombre' => 'Pizza',
        'Emoji' => '🍕',
    ]);
    $meta->recompensas()->attach($oldReward->ID_Recompensa, ['Habito_ID' => $habito->ID_Habito]);

    $response = $this->actingAs($user)->patch(route('goals.edit'), [
        'habito_id' => $habito->ID_Habito,
        'racha_requerida' => 14,
        'repetible' => 0,
        'recompensa_id' => $newReward->ID_Recompensa,
    ]);

    $response->assertRedirect(route('goals'));

    $meta->refresh();

    expect($meta->Repetible)->toBeFalse()
        ->and($meta->recompensas->pluck('ID_Recompensa')->all())->toBe([$newReward->ID_Recompensa]);
});

it('deletes a goal with its reward links and deliveries but keeps the reward', function () {
    $user = makeGoalsUser();
    $habito = Habito::forceCreate([
        'Usuario_ID' => $user->ID_Usuario,
        'Objetivo' => 'Caminar',
        'Prioridad' => 'Baja',
        'Frecuencia' => 'Daria',
    ]);
    $meta = Meta::create([
        'Habito_ID' => $habito->ID_Habito,
        'RachaRequerida' => 9,
        'Repetible' => true,
    ]);
    $recompensa = Recompensa::create([
        'Usuario_ID' => $user->ID_Usuario,
        'Nombre' => 'Helado',
        'Emoji' => '🍦',
    ]);
    $meta->recompensas()->attach($recompensa->ID_Recompensa, ['Habito_ID' => $habito->ID_Habito]);

    DB::table('cumplimiento_meta')->insert([
        'Habito_ID' => $habito->ID_Habito,
        'RachaRequerida' => 9,
        'MarcaCumplimiento' => now(),
        'RachaAlCumplir' => 9,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($user)->delete(route('goals.delete'), [
        'habito_id' => $habito->ID_Habito,
        'racha_requerida' => 9,
    ])->assertRedirect(route('goals'));

    expect(Meta::where('Habito_ID', $habito->ID_Habito)->exists())->toBeFalse()
        ->and(DB::table('recompensas_meta')->where('Habito_ID', $habito->ID_Habito)->exists())->toBeFalse()
        ->and(DB::table('cumplimiento_meta')->where('Habito_ID', $habito->ID_Habito)->exists())->toBeFalse()
        ->and(Recompensa::find($recompensa->ID_Recompensa))->not->toBeNull();
});

it('does not allow linking a reward owned by another user', function () {
    $user = makeGoalsUser();
    $otherUser = User::create([
        'NombreUsuario' => 'other',
        'email' => 'other@example.com',
        'password' => Hash::make('password'),
        'Avatar_URL' => 'https://example.com/b.png',
        'Sexo' => 'F',
    ]);
    $habito = Habito::forceCreate([
        'Usuario_ID' => $user->ID_Usuario,
        'Objetivo' => 'Nadar',
        'Prioridad' => 'Media',
        'Frecuencia' => 'Semanal',
    ]);
    $foreignReward = Recompensa::create([
        'Usuario_ID' => $otherUser->ID_Usuario,
        'Nombre' => 'Regalo ajeno',
        'Emoji' => '🎁',
    ]);

    $this->actingAs($user)->post(route('goals.create'), [
        'habito_id' => $habito->ID_Habito,
        'racha_requerida' => 8,
        'repetible' => 1,
        'recompensa_id' => $foreignReward->ID_Recompensa,
    ])->assertRedirect(route('goals'));

    $meta = Meta::where('Habito_ID', $habito->ID_Habito)->where('RachaRequerida', 8)->firstOrFail();

    expect($meta->recompensas)->toBeEmpty();
});

it('detaches a reward from its goals when the reward is deleted', function () {
    $user = makeGoalsUser();
    $habito = Habito::forceCreate([
        'Usuario_ID' => $user->ID_Usuario,
        'Objetivo' => 'Escribir',
        'Prioridad' => 'Alta',
        'Frecuencia' => 'Daria',
    ]);
    $meta = Meta::create([
        'Habito_ID' => $habito->ID_Habito,
        'RachaRequerida' => 45,
        'Repetible' => true,
    ]);
    $recompensa = Recompensa::create([
        'Usuario_ID' => $user->ID_Usuario,
        'Nombre' => 'Libro',
        'Emoji' => '📚',
    ]);
    $meta->recompensas()->attach($recompensa->ID_Recompensa, ['Habito_ID' => $habito->ID_Habito]);

    $this->actingAs($user)->delete(route('reward.delete'), [
        'recompensa_id' => $recompensa->ID_Recompensa,
    ])->assertRedirect(route('goals'));

    expect(Recompensa::find($recompensa->ID_Recompensa))->toBeNull()
        ->and($meta->fresh()->recompensas)->toBeEmpty();
});
