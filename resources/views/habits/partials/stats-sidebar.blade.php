@php
    $todayCompletedHabits = auth()->user()->getTodaysCompletedHabits();
    $todayTotalHabits = auth()->user()->getTodaysHabitCount();

    $heatmapStart = auth()->user()->getHeatmapStart();
    $heatmapCounts = auth()->user()->getHeatmapData();
    $totalHabits = max(1, auth()->user()->getTotalHabitCount());

    $heatLevels = ['#e0e7ee', '#BAE6FD', '#7DD3FC', '#38BDF8', '#0284C7'];

    function heatColorFor(float $ratio, array $levels): string
    {
        if ($ratio <= 0) {
            return $levels[0];
        }
        if ($ratio <= 0.25) {
            return $levels[1];
        }
        if ($ratio <= 0.5) {
            return $levels[2];
        }
        if ($ratio <= 0.75) {
            return $levels[3];
        }

        return $levels[4];
    }
@endphp

<div>
    <div class="mb-5 grid grid-cols-1 gap-3">
        <div class="rounded-[10px] bg-white p-3.5 text-center shadow-card">
            <div class="text-[26px] font-bold leading-tight text-brand-80">
                @if ($todayTotalHabits > 0)
                    {{ $todayCompletedHabits }}/{{ $todayTotalHabits }}
                @else
                    Sin hábitos.
                @endif
            </div>
            <div class="mt-0.5 text-xs text-ink-light">Completado hoy</div>
        </div>
        <div class="rounded-[10px] bg-white p-3.5 text-center shadow-card">
            <div class="text-[26px] font-bold leading-tight text-orange">{{ auth()->user()->getCurrentRacha() }}<i
                    class="ti ti-bolt"></i></div>
            <div class="mt-0.5 text-xs text-ink-light">Racha actual</div>
        </div>
        <div class="rounded-[10px] bg-white p-3.5 text-center shadow-card">
            <div class="text-[26px] font-bold leading-tight text-brand-80">
                {{ auth()->user()->getWeekCompletedPercentage() }}%</div>
            <div class="mt-0.5 text-xs text-ink-light">Esta semana</div>
        </div>
    </div>

    <h2 class="mt-1 mb-5 text-base font-bold text-brand-90">Progreso rápido</h2>
    <div class="rounded-[10px] bg-white p-4 shadow-card">
        <div class="flex flex-col gap-[3px] py-1">
            @for ($week = 0; $week < 12; $week++)
                <div class="flex gap-[3px]">
                    @for ($day = 0; $day < 7; $day++)
                        @php
                            $date = $heatmapStart->copy()->addWeeks($week)->addDays($day);
                            $future = $date->isAfter(now());
                            $n = $heatmapCounts[$date->format('Y-m-d')] ?? 0;
                            $color = $future
                                ? 'rgba(208, 216, 226, 0.35)'
                                : heatColorFor($n / $totalHabits, $heatLevels);
                            $label = $future ? '' : 'title="' . $date->format('d/m/Y') . ' · ' . $n . ($n === 1 ? ' cumplimiento' : ' cumplimientos') . '"';
                        @endphp
                        <div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px]"
                            style="background-color: {{ $color }}" {!! $label !!}
                            data-date="{{ $date->format('Y-m-d') }}">
                        </div>
                    @endfor
                </div>
            @endfor
        </div>
        <p class="mt-2 text-[11px] text-ink-light">Últimas 12 semanas</p>
    </div>
</div>