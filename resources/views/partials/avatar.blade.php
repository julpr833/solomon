@props(['user' => null])

@php
    $name = $user?->NombreUsuario ?: 'Usuario';
    $initial = strtoupper(mb_substr(trim($name), 0, 1));
@endphp

<div class="relative bg-sky-200 py-1 px-2.5 rounded-md shadow-md shadow-black/25 transition-colors hover:bg-sky-100"
    id="user-menu">
    <button id="user-menu-btn" type="button" aria-haspopup="true" aria-expanded="false"
        class="flex cursor-pointer items-center gap-2 rounded-full border-transparent bg-transparent p-0 transition-colors hover:opacity-90">
        <span class="max-w-27.5 truncate text-md font-semibold text-ink sm:max-w-35">{{ $name }}</span>
        <div class="avatar" title="{{ $name }}">
            <span class="avatar-initials">{{ $initial }}</span>
            @if ($user?->Avatar_URL)
                <img src="{{ $user->Avatar_URL }}" alt="Avatar de {{ $name }}" onerror="this.style.display = 'none'">
            @endif
        </div>
    </button>

    <div id="user-menu-dropdown"
        class="absolute top-full right-0 z-50 mt-2 hidden w-52 overflow-hidden rounded-[10px] border border-line bg-white py-1.5 shadow-card">
        <a href="{{ route('settings') }}"
            class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-ink transition-colors hover:bg-page">
            <i class="ti ti-settings text-brand-80"></i> Configuración
        </a>
        <hr class="border-line my-1">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex w-full cursor-pointer items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-danger transition-colors hover:bg-[#FEEBEA]">
                <i class="ti ti-logout"></i> Cerrar sesión
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const menu = document.getElementById('user-menu');
        if (!menu) return;

        const btn = document.getElementById('user-menu-btn');
        const dropdown = document.getElementById('user-menu-dropdown');

        function closeMenu() {
            dropdown.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
        }

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = !dropdown.classList.contains('hidden');
            if (isOpen) {
                closeMenu();
            } else {
                dropdown.classList.remove('hidden');
                btn.setAttribute('aria-expanded', 'true');
            }
        });

        document.addEventListener('click', (e) => {
            if (!menu.contains(e.target)) closeMenu();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeMenu();
        });
    });
</script>