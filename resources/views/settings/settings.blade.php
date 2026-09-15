@extends('layouts.app')

@section('title', 'Solomon — Configuración')

@section('topbar')
    <x-topbar title="Configuración"></x-topbar>
@endsection

@section('content')
    <div class="mb-4 rounded-[10px] bg-white p-4 shadow-card">
        <div class="flex flex-col items-center gap-3 py-4 text-center">
            <div
                class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-brand-70 text-2xl font-bold text-white">
                {{ strtoupper(mb_substr(auth()->user()?->name ?: 'J', 0, 1)) }}
            </div>
            <div>
                <p class="font-semibold">{{ auth()->user()?->name ?: 'Juan Pérez' }}</p>
                <p class="text-[13px] text-ink-light">{{ auth()->user()?->email ?: 'juan@correo.com' }}</p>
                <button type="button"
                    class="mt-2 inline-flex w-auto items-center justify-center gap-1.5 px-4 py-2 rounded-lg font-semibold text-[13px] cursor-pointer bg-transparent text-brand-70 border-[1.5px] border-brand-70 hover:bg-brand-70 hover:text-white transition-opacity duration-200 active:scale-[0.98]">Cambiar
                    foto</button>
            </div>
        </div>
        <div class="mx-auto my-4 mb-6 flex w-[clamp(300px,70%,440px)] flex-row justify-around gap-3">
            <div class="flex flex-row items-center gap-3 rounded-xl bg-green-bg px-6 py-3.5">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green text-lg text-white"><i
                        class="ti ti-list"></i></div>
                <div>
                    <h4 class="text-[20px] font-bold leading-none text-ink">5</h4>
                    <p class="mt-0.5 text-xs text-ink-light">Hábitos creados</p>
                </div>
            </div>
            <div class="flex flex-row items-center gap-3 rounded-xl bg-orange-bg px-6 py-3.5">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-orange text-lg text-white"><i
                        class="ti ti-bolt"></i></div>
                <div>
                    <h4 class="text-[20px] font-bold leading-none text-ink">42</h4>
                    <p class="mt-0.5 text-xs text-ink-light">Máxima racha</p>
                </div>
            </div>
        </div>
        <div class="mb-0">
            <label for="bio" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Biografía</label>
            <textarea id="bio" name="biografia"
                class="block w-full resize-y min-h-20 px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                placeholder="Cuenta algo sobre ti..."></textarea>
        </div>
    </div>

    <h2 class="my-5 text-base font-bold text-brand-90">Notificaciones</h2>
    <div class="mb-4 rounded-[10px] bg-white p-4 shadow-card">
        <label class="flex cursor-pointer select-none items-center justify-between border-b border-line py-3.5">
            <div>
                <div class="text-sm font-medium">Recordatorios WhatsApp</div>
                <div class="text-xs text-ink-light">Recibe avisos según tu frecuencia</div>
            </div>
            <input type="checkbox" id="toggle-whatsapp" class="peer sr-only" checked>
            <span
                class="relative h-5 w-10 shrink-0 rounded-full bg-line transition-colors duration-200 after:absolute after:top-[2px] after:left-[2px] after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-transform after:duration-200 after:content-[''] peer-checked:bg-brand-50 peer-checked:after:translate-x-5"></span>
        </label>
        <div class="py-3 pb-1" id="whatsapp-options-panel">
            <div class="mb-3">
                <label for="rem-freq" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Frecuencia</label>
                <select id="rem-freq"
                    class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                    onchange="document.getElementById('rem-days').style.display = this.value === 'Días por semana' ? 'flex' : 'none';">
                    <option>Diario</option>
                    <option>Días por semana</option>
                    <option>Una vez por semana</option>
                </select>
            </div>
            <div id="rem-days" class="mt-1 flex justify-between gap-2" style="display: none;">
                <span
                    class="flex h-6 w-6 items-center justify-center rounded-lg bg-page text-xs font-semibold text-ink-light">L</span>
                <span
                    class="flex h-6 w-6 items-center justify-center rounded-lg bg-brand-50 text-xs font-semibold text-white">M</span>
                <span
                    class="flex h-6 w-6 items-center justify-center rounded-lg bg-brand-50 text-xs font-semibold text-white">M</span>
                <span
                    class="flex h-6 w-6 items-center justify-center rounded-lg bg-brand-50 text-xs font-semibold text-white">J</span>
                <span
                    class="flex h-6 w-6 items-center justify-center rounded-lg bg-page text-xs font-semibold text-ink-light">V</span>
                <span
                    class="flex h-6 w-6 items-center justify-center rounded-lg bg-page text-xs font-semibold text-ink-light">S</span>
                <span
                    class="flex h-6 w-6 items-center justify-center rounded-lg bg-page text-xs font-semibold text-ink-light">D</span>
            </div>
            <div class="mt-3 mb-0">
                <label for="rem-time" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Horario de envío</label>
                <input type="time" id="rem-time"
                    class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                    value="20:00">
            </div>
        </div>
        <label class="flex cursor-pointer select-none items-center justify-between border-b border-line py-3.5">
            <div>
                <div class="text-sm font-medium">Felicitar al cumplir metas</div>
                <div class="text-xs text-ink-light">Mensaje cuando logres una meta</div>
            </div>
            <input type="checkbox" id="toggle-goals-congrats" class="peer sr-only" checked>
            <span
                class="relative h-5 w-10 shrink-0 rounded-full bg-line transition-colors duration-200 after:absolute after:top-[2px] after:left-[2px] after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-transform after:duration-200 after:content-[''] peer-checked:bg-brand-50 peer-checked:after:translate-x-5"></span>
        </label>
    </div>

    <h2 class="my-5 text-base font-bold text-brand-90">Contenido motivacional</h2>
    <div class="mb-4 rounded-[10px] bg-white p-4 shadow-card">
        <label class="flex cursor-pointer select-none items-center justify-between border-b border-line py-3.5">
            <div>
                <div class="text-sm font-medium">Proverbios bíblicos</div>
                <div class="text-xs text-ink-light">Mensajes del libro de Proverbios</div>
            </div>
            <input type="checkbox" id="toggle-proverbs" class="peer sr-only" checked>
            <span
                class="relative h-5 w-10 shrink-0 rounded-full bg-line transition-colors duration-200 after:absolute after:top-[2px] after:left-[2px] after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-transform after:duration-200 after:content-[''] peer-checked:bg-brand-50 peer-checked:after:translate-x-5"></span>
        </label>
        <label class="flex cursor-pointer select-none items-center justify-between py-3.5">
            <div>
                <div class="text-sm font-medium">Frases motivacionales</div>
                <div class="text-xs text-ink-light">Frases de autores varios</div>
            </div>
            <input type="checkbox" id="toggle-quotes" class="peer sr-only" checked>
            <span
                class="relative h-5 w-10 shrink-0 rounded-full bg-line transition-colors duration-200 after:absolute after:top-[2px] after:left-[2px] after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-transform after:duration-200 after:content-[''] peer-checked:bg-brand-50 peer-checked:after:translate-x-5"></span>
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
        const toggles = [
            { id: 'toggle-whatsapp', key: 'recordatoriosWhatsapp', defaultValue: true },
            { id: 'toggle-goals-congrats', key: 'felicitarMetas', defaultValue: true },
            { id: 'toggle-proverbs', key: 'proverbiosBiblicos', defaultValue: true },
            { id: 'toggle-quotes', key: 'frasesMotivacionales', defaultValue: true }
        ];

        document.addEventListener('DOMContentLoaded', () => {
            toggles.forEach(t => {
                const element = document.getElementById(t.id);
                if (!element) return;

                const savedVal = localStorage.getItem(t.key);
                element.checked = savedVal === null ? t.defaultValue : savedVal === 'true';

                if (t.id === 'toggle-whatsapp') updateWhatsappPanel(element.checked);

                element.addEventListener('change', () => {
                    localStorage.setItem(t.key, String(element.checked));
                    if (t.id === 'toggle-whatsapp') updateWhatsappPanel(element.checked);
                });
            });

            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                const savedPhone = localStorage.getItem('whatsappPhone');
                if (savedPhone !== null) phoneInput.value = savedPhone;

                phoneInput.addEventListener('input', () => {
                    localStorage.setItem('whatsappPhone', phoneInput.value.trim());
                });
            }
        });

        function updateWhatsappPanel(visible) {
            const panel = document.getElementById('whatsapp-options-panel');
            if (panel) panel.style.display = visible ? 'block' : 'none';
        }
    </script>
@endsection