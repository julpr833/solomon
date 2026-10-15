@extends('layouts.app')

@section('title', 'Solomon — Configuración')

@section('topbar')
    <x-topbar title="Configuración"></x-topbar>
@endsection

@section('content')
    @php
        $settingUser = auth()->user();
        $name = $settingUser?->NombreUsuario ?: 'Usuario';
        $initial = strtoupper(mb_substr(trim($name), 0, 1));
    @endphp

    <div class="mb-4 rounded-[10px] bg-white p-4 shadow-card">
        <div class="flex flex-col items-center gap-3 py-4 text-center">
            <div class="avatar avatar-lg" title="{{ $name }}">
                <span class="avatar-initials">{{ $initial }}</span>
                @if ($settingUser?->Avatar_URL)
                    <img src="{{ $settingUser->Avatar_URL }}" alt="Avatar de {{ $name }}" onerror="this.style.display = 'none'">
                @endif
            </div>
            <div>
                <p class="font-semibold">{{ $name }}</p>
                <p class="text-[13px] text-ink-light">{{ $settingUser?->email ?: 'juan@correo.com' }}</p>
            </div>
        </div>
        <div class="mx-auto my-4 mb-6 flex w-[clamp(300px,70%,440px)] flex-row justify-around gap-3">
            <div class="flex flex-row items-center gap-3 rounded-xl bg-green-bg px-6 py-3.5">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green text-lg text-white"><i
                        class="ti ti-list"></i></div>
                <div>
                    <h4 class="text-[20px] font-bold text-center leading-none text-ink">
                        {{ auth()->user()?->habitos()->count() }}
                    </h4>
                    <p class="mt-0.5 text-xs text-ink-light">Hábitos creados</p>
                </div>
            </div>
            <div class="flex flex-row items-center gap-3 rounded-xl bg-orange-bg px-6 py-3.5">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-orange text-lg text-white"><i
                        class="ti ti-bolt"></i></div>
                <div>
                    <h4 class="text-[20px] font-bold text-center leading-none text-ink">{{ auth()->user()?->getMaxRacha() }}
                    </h4>
                    <p class="mt-0.5 text-xs text-ink-light">Máxima racha</p>
                </div>
            </div>
        </div>
        <div class="relative mb-0">
            <label for="bio" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Biografía</label>
            <textarea id="bio" name="biografia"
                class="block w-full resize-y min-h-20 px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                placeholder="Cuenta algo sobre ti...">{{ $settingUser?->Biografia }}</textarea>
            <div id="bioSaved"
                class="pointer-events-none absolute bottom-3 right-3 hidden items-center gap-1 rounded-md bg-green px-2.5 py-1 text-[11px] font-semibold text-white shadow-card">
                <i class="ti ti-check"></i> Guardado
            </div>
        </div>
    </div>

    <h2 class="my-5 text-base font-bold text-brand-90">Contenido motivacional</h2>
    <div class="mb-4 rounded-[10px] bg-white p-4 shadow-card">
        <label class="flex cursor-pointer select-none items-center justify-between border-b border-line py-3.5">
            <div>
                <div class="text-sm font-medium">Proverbios bíblicos</div>
                <div class="text-xs text-ink-light">Mensajes del libro de Proverbios</div>
            </div>
            <input type="checkbox" id="toggle-proverbs" class="peer sr-only" @checked(auth()->user()?->wantsProverbios())>
            <span
                class="relative h-5 w-10 shrink-0 rounded-full bg-line transition-colors duration-200 after:absolute after:top-0.5 after:left-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-transform after:duration-200 after:content-[''] peer-checked:bg-brand-50 peer-checked:after:translate-x-5"></span>
        </label>
        <label class="flex cursor-pointer select-none items-center justify-between py-3.5">
            <div>
                <div class="text-sm font-medium">Frases motivacionales</div>
                <div class="text-xs text-ink-light">Frases de autores varios</div>
            </div>
            <input type="checkbox" id="toggle-quotes" class="peer sr-only" @checked(auth()->user()?->wantsFrases())>
            <span
                class="relative h-5 w-10 shrink-0 rounded-full bg-line transition-colors duration-200 after:absolute after:top-0.5 after:left-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-transform after:duration-200 after:content-[''] peer-checked:bg-brand-50 peer-checked:after:translate-x-5"></span>
        </label>
    </div>

    <h2 class="my-5 text-base font-bold text-brand-90">Número de teléfono</h2>
    <div class="mb-6 rounded-[10px] bg-white p-4 shadow-card">
        <div class="mb-2">
            <label for="phone" class="mb-1.5 block text-[13px] font-semibold text-brand-95">WhatsApp</label>
            <input type="text" id="phone"
                class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                placeholder="Ej: +54 11 1234-5678" value="{{ auth()->user()?->telefono ?? '' }}">
        </div>
        <p class="text-xs text-ink-light">Usado para recordatorios por WhatsApp</p>
    </div>

    <div class="text-center mb-14">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="inline-flex w-auto items-center justify-center gap-1.5 px-8 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-danger text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]"><i
                    class="ti ti-logout"></i> Cerrar sesión</button>
        </form>
    </div>
@endsection

@section('footer')
    @include('partials.bottom-nav', ['active' => 'settings'])
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const provToggle = document.getElementById('toggle-proverbs');
            const quoteToggle = document.getElementById('toggle-quotes');
            const phoneInput = document.getElementById('phone');
            const biography = document.getElementById('bio');

            var biographySaveTimer = null;

            const savePreferences = () => {
                fetch('{{ route('settings.preferences') }}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        proverbios: provToggle.checked,
                        frases: quoteToggle.checked,
                    }),
                });
            };

            const bioSaved = document.getElementById('bioSaved');
            let bioSavedTimer = null;
            let lastSavedBiography = bio ? bio.value : null;

            const pulseBiography = () => {
                if (!bio) return;
                bio.classList.remove('input-saved-pulse');
                void bio.offsetWidth;
                bio.classList.add('input-saved-pulse');
            };

            if (bio) {
                bio.addEventListener('animationend', (event) => {
                    if (event.animationName === 'input-saved-pulse') {
                        bio.classList.remove('input-saved-pulse');
                    }
                });
            }

            const showBiographySaved = () => {
                if (!bioSaved) return;
                clearTimeout(bioSavedTimer);
                bioSaved.classList.remove('hidden', 'toast-saved');
                bioSaved.classList.add('flex');
                void bioSaved.offsetWidth;
                bioSaved.classList.add('toast-saved');
                bioSavedTimer = setTimeout(() => {
                    bioSaved.classList.add('hidden');
                    bioSaved.classList.remove('flex', 'toast-saved');
                }, 2000);
            };

            const saveBiography = () => {

                if (biographySaveTimer)
                    clearTimeout(biographySaveTimer)

                biographySaveTimer = setTimeout(() => {
                    const value = bio.value;

                    if (value === lastSavedBiography)
                        return;

                    fetch('{{ route('settings.biography') }}', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            biografia: value,
                        }),
                    }).then((response) => {
                        if (response.ok) {
                            lastSavedBiography = value;
                            pulseBiography();
                            showBiographySaved();
                        }
                    });
                }, 1000);
            };

            if (provToggle) provToggle.addEventListener('change', savePreferences);
            if (quoteToggle) quoteToggle.addEventListener('change', savePreferences);
            if (bio) bio.addEventListener('input', saveBiography);

            if (phoneInput) {
                const savedPhone = localStorage.getItem('whatsappPhone');
                if (savedPhone !== null) phoneInput.value = savedPhone;

                phoneInput.addEventListener('input', () => {
                    localStorage.setItem('whatsappPhone', phoneInput.value.trim());
                });
            }
        });
    </script>
@endsection