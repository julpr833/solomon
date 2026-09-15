@props([
    'title' => '',
    'back' => null,
    'icon' => null,
])

<header class="sticky top-0 z-10 flex items-center gap-3 px-5 py-4 bg-white border-b border-line">
    @if ($back)
        <a href="{{ $back }}" class="text-[22px] text-brand-80 leading-none cursor-pointer hover:text-brand-50 transition">
            <i class="ti ti-arrow-left"></i>
        </a>
    @elseif ($icon)
        <i class="ti {{ $icon }} text-[22px] text-brand-80"></i>
    @endif

    <h1 class="flex-1 text-xl font-extrabold text-brand-90 tracking-[-0.3px]">{{ $title }}</h1>

    @isset($right)
        {{ $right }}
    @endisset
</header>