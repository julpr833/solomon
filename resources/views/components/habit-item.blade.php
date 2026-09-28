@props([
    'name' => '',
    'frequency' => 'Diario',
    'priority' => 'Media',
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
    onclick="location.href='{{ $href }}'">
    <div
        class="flex h-5.5 w-5.5 shrink-0 items-center justify-center rounded-md border-2 text-sm text-white transition-colors duration-150 {{ $done ? 'border-brand-50 bg-brand-50' : 'border-brand-70' }}">
        @if ($done)
            <i class="ti ti-check"></i>
        @endif
    </div>
    <div class="flex-1">
        <h3 class="text-[15px] font-semibold">{{ $name }}</h3>
        <div class="mt-0.5 text-xs text-ink-light">{{ $frequency }} · Prioridad {{ strtolower($priority) }}</div>
    </div>
    <span class="rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $badgeClass }}">{{ $frequency }}</span>
    <span class="whitespace-nowrap text-[13px] font-semibold text-orange">{{ $streak }} <i
            class="ti ti-bolt"></i></span>
    <div class="flex shrink-0 gap-0.5 text-ink-light">
        <i class="ti ti-pencil cursor-pointer rounded-md p-1.5 text-[17px] transition-colors duration-150 hover:bg-page hover:text-brand-70"
            onclick="event.stopPropagation(); openEdit('{{ $name }}','{{ $frequency }}','{{ $priority }}')"></i>
        <i class="ti ti-trash cursor-pointer rounded-md p-1.5 text-[17px] transition-colors duration-150 hover:bg-[#FEEBEA] hover:text-danger"
            onclick="event.stopPropagation(); openDelete('{{ $name }}')"></i>
    </div>
</div>