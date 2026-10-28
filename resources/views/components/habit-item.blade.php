@props([
    'id' => null,
    'name' => '',
    'description' => '',
    'frequency' => 'Diario',
    'priority' => 'Media',
    'reminder' => '',
    'streak' => 0,
    'done' => false,
    'href' => '#',
])

@php
    $badgeClass = match ($frequency) {
        'Semanal' => 'bg-[#FFF3E0] text-[#E65100]',
        'Mensual' => 'bg-[#E8F5E9] text-[#2E7D32]',
        default => 'bg-[#E3F2FD] text-brand-80',
    };
@endphp

<div class="flex cursor-pointer items-center gap-3 border-b border-line py-3.5 last:border-b-0"
    data-habit-id="{{ $id }}" data-habit-name="{{ $name }}" data-habit-description="{{ $description }}"
    data-habit-frequency="{{ $frequency }}" data-habit-priority="{{ $priority }}"
    data-habit-reminder="{{ $reminder }}"
    onclick="location.href='{{ $href }}'">
    <button type="button" @if ($id) data-complete-url="{{ route('habit.complete', $id) }}" @endif
        class="flex h-5.5 w-5.5 shrink-0 cursor-pointer items-center justify-center rounded-md border-2 p-0 text-sm text-white transition-colors duration-150 disabled:cursor-wait disabled:opacity-60 {{ $done ? 'border-brand-50 bg-brand-50' : 'border-brand-70' }}"
        aria-label="{{ $done ? 'Hábito completado hoy' : 'Marcar hábito como completado' }}"
        aria-pressed="{{ $done ? 'true' : 'false' }}"
        onclick="event.stopPropagation(); toggleHabit(this)">
        @if ($done)
            <i class="ti ti-check"></i>
        @endif
    </button>
    <div class="flex-1">
        <h3 class="text-[15px] font-semibold">{{ $name }}</h3>
        <div class="mt-0.5 text-xs text-ink-light">{{ $frequency }} · Prioridad {{ strtolower($priority) }}</div>
    </div>
    <span class="rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $badgeClass }}">{{ $frequency }}</span>
    <span data-habit-streak
        class="whitespace-nowrap text-[13px] font-semibold text-orange">{{ $streak }} <i
            class="ti ti-bolt"></i></span>
    <div class="flex shrink-0 gap-0.5 text-ink-light">
        <i class="ti ti-pencil cursor-pointer rounded-md p-1.5 text-[17px] transition-colors duration-150 hover:bg-page hover:text-brand-70"
            onclick="event.stopPropagation(); openEdit(this)"></i>
        <i class="ti ti-trash cursor-pointer rounded-md p-1.5 text-[17px] transition-colors duration-150 hover:bg-[#FEEBEA] hover:text-danger"
            onclick="event.stopPropagation(); openDelete(this)"></i>
    </div>
</div>