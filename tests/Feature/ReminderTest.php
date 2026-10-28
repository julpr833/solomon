<?php

use App\Contracts\WhatsAppGateway;
use App\Enums\ReminderStatus;
use App\Jobs\SendHabitReminder;
use App\Models\Habito;
use App\Models\Recordatorio;
use App\Models\User;
use App\Services\WhatsApp\WhatsAppException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Mockery\MockInterface;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('creates a reminder when a habit is created with a time', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('habit.create'), [
        'objetivo' => 'Leer',
        'frecuencia' => 'Daria',
        'prioridad' => 'Media',
        'hora_recordatorio' => '09:30',
    ])->assertRedirect(route('dashboard'));

    $habit = Habito::where('Usuario_ID', $user->ID_Usuario)->firstOrFail();
    $reminder = Recordatorio::where('Habito_ID', $habit->ID_Habito)->firstOrFail();

    expect($reminder->ProximaFecha->format('H:i'))->toBe('09:30')
        ->and($reminder->Estado)->toBe(ReminderStatus::Pending);
});

it('updates and removes the reminder when editing a habit', function () {
    $user = User::factory()->create();
    $habit = Habito::factory()->for($user, 'usuario')->create();
    $reminder = Recordatorio::create([
        'Habito_ID' => $habit->ID_Habito,
        'ProximaFecha' => now()->addDay(),
        'Mensaje' => 'Recordatorio: '.$habit->Objetivo,
    ]);

    $this->actingAs($user)->patch(route('habit.edit'), [
        'habito_id' => $habit->ID_Habito,
        'objetivo' => $habit->Objetivo,
        'frecuencia' => $habit->Frecuencia,
        'prioridad' => $habit->Prioridad,
        'hora_recordatorio' => '18:15',
    ])->assertRedirect(route('dashboard'));

    expect($reminder->fresh()->ProximaFecha->format('H:i'))->toBe('18:15');

    $this->actingAs($user)->patch(route('habit.edit'), [
        'habito_id' => $habit->ID_Habito,
        'objetivo' => $habit->Objetivo,
        'frecuencia' => $habit->Frecuencia,
        'prioridad' => $habit->Prioridad,
        'hora_recordatorio' => '',
    ])->assertRedirect(route('dashboard'));

    expect(Recordatorio::where('Habito_ID', $habit->ID_Habito)->exists())->toBeFalse();
});

it('dispatches only due pending reminders', function () {
    Queue::fake();

    $user = User::factory()->create(['telefono' => '+5491123456789']);
    $dueHabit = Habito::factory()->for($user, 'usuario')->create();
    $futureHabit = Habito::factory()->for($user, 'usuario')->create();

    $due = Recordatorio::create([
        'Habito_ID' => $dueHabit->ID_Habito,
        'ProximaFecha' => now()->subMinute(),
    ]);
    Recordatorio::create([
        'Habito_ID' => $futureHabit->ID_Habito,
        'ProximaFecha' => now()->addHour(),
    ]);

    Artisan::call('reminders:dispatch');

    Queue::assertPushed(SendHabitReminder::class, 1);
    Queue::assertPushed(SendHabitReminder::class, fn (SendHabitReminder $job): bool => $job->recordatorio->is($due));
});

it('sends a reminder and schedules the next occurrence', function () {
    $user = User::factory()->create(['telefono' => '+5491123456789']);
    $habit = Habito::factory()->for($user, 'usuario')->create([
        'Objetivo' => 'Leer',
        'Frecuencia' => 'Daria',
    ]);
    $reminder = Recordatorio::create([
        'Habito_ID' => $habit->ID_Habito,
        'ProximaFecha' => now()->subMinute(),
    ]);

    $this->mock(WhatsAppGateway::class, function (MockInterface $mock): void {
        $mock->shouldReceive('send')
            ->once()
            ->with('+5491123456789', '⏰ Recordatorio: Leer');
    });

    (new SendHabitReminder($reminder))->handle(app(WhatsAppGateway::class));

    $reminder->refresh();

    expect($reminder->Estado)->toBe(ReminderStatus::Pending)
        ->and($reminder->EnviadoEn)->not->toBeNull()
        ->and($reminder->Intentos)->toBe(0)
        ->and($reminder->ProximaFecha->isFuture())->toBeTrue();
});

it('marks a reminder as failed when the user has no phone', function () {
    $user = User::factory()->create(['telefono' => null]);
    $habit = Habito::factory()->for($user, 'usuario')->create();
    $reminder = Recordatorio::create([
        'Habito_ID' => $habit->ID_Habito,
        'ProximaFecha' => now()->subMinute(),
    ]);

    $this->mock(WhatsAppGateway::class, function (MockInterface $mock): void {
        $mock->shouldNotReceive('send');
    });

    (new SendHabitReminder($reminder))->handle(app(WhatsAppGateway::class));

    expect($reminder->fresh()->Estado)->toBe(ReminderStatus::Failed)
        ->and($reminder->fresh()->UltimoError)->toContain('teléfono');
});

it('records gateway errors and marks the job failed after retries', function () {
    $user = User::factory()->create(['telefono' => '+5491123456789']);
    $habit = Habito::factory()->for($user, 'usuario')->create();
    $reminder = Recordatorio::create([
        'Habito_ID' => $habit->ID_Habito,
        'ProximaFecha' => now()->subMinute(),
    ]);

    $this->mock(WhatsAppGateway::class, function (MockInterface $mock): void {
        $mock->shouldReceive('send')->once()->andThrow(new WhatsAppException('WAHA caído'));
    });

    $job = new SendHabitReminder($reminder);

    expect(fn () => $job->handle(app(WhatsAppGateway::class)))
        ->toThrow(WhatsAppException::class, 'WAHA caído');

    expect($reminder->fresh()->Intentos)->toBe(1)
        ->and($reminder->fresh()->UltimoError)->toContain('WAHA caído');

    $job->failed(new WhatsAppException('WAHA caído'));

    expect($reminder->fresh()->Estado)->toBe(ReminderStatus::Failed);
});

it('registers the reminder dispatch command every minute', function () {
    $events = app(Schedule::class)->events();

    $event = collect($events)->first(
        fn ($event): bool => str_contains((string) $event->command, 'reminders:dispatch'),
    );

    expect($event)->not->toBeNull()
        ->and($event->expression)->toBe('* * * * *');
});
