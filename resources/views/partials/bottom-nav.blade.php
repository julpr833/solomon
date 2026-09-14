@props(['active' => 'habits'])

<nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[960px] bg-white border-t border-line flex z-20">
    <a
        href="{{ route('dashboard') }}"
        class="flex-1 text-center py-2.5 pb-[calc(0.5rem+env(safe-area-inset-bottom))] text-[11px] text-ink-light transition transition-colors @if($active === 'habits') !text-brand-50 font-semibold @endif"
    >
        <span class="block text-[22px] mb-0.5 leading-none"><i class="ti ti-list"></i></span>
        Hábitos
    </a>
    <a
        href="{{ route('goals') }}"
        class="flex-1 text-center py-2.5 pb-[calc(0.5rem+env(safe-area-inset-bottom))] text-[11px] text-ink-light transition transition-colors @if($active === 'goals') !text-brand-50 font-semibold @endif"
    >
        <span class="block text-[22px] mb-0.5 leading-none"><i class="ti ti-trophy"></i></span>
        Metas
    </a>
    <a
        href="{{ route('settings') }}"
        class="flex-1 text-center py-2.5 pb-[calc(0.5rem+env(safe-area-inset-bottom))] text-[11px] text-ink-light transition transition-colors @if($active === 'settings') !text-brand-50 font-semibold @endif"
    >
        <span class="block text-[22px] mb-0.5 leading-none"><i class="ti ti-settings"></i></span>
        Ajustes
    </a>
</nav>