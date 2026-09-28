@extends('layouts.app')

@section('title', 'Solomon — Metas y Recompensas')

@section('topbar')
    <x-topbar title="Metas y Recompensas" icon="ti-trophy">
        <x-slot:right>
            @include('partials.avatar', ['user' => auth()->user()])
        </x-slot:right>
    </x-topbar>
@endsection

@section('content')
    <h2 class="my-5 text-base font-bold text-brand-90">Metas activas</h2>

    <div class="rounded-[10px] bg-white p-4 shadow-card">
        <div class="flex items-center gap-3 border-b border-line py-3.5 last:border-b-0">
            <div class="flex-1">
                <div class="text-[13px] font-semibold text-ink"><small class="font-normal !text-brand-70">Leer 15 min</small> · 30 días seguidos <span class="text-ink-light">· 12/30</span></div>
                <div class="mt-1.5 h-1.5 overflow-hidden rounded-[3px] bg-line">
                    <div class="h-full rounded-[3px] bg-brand-50 transition-[width] duration-300" style="width: 40%;"></div>
                </div>
            </div>
            <div class="flex w-20 shrink-0 flex-col items-center gap-0.5">
                <div class="text-lg leading-none">🍦</div>
                <div class="text-center text-[11px] leading-tight text-ink-light">Helado de chocolate</div>
            </div>
        </div>
        <div class="flex items-center gap-3 border-b border-line py-3.5 last:border-b-0">
            <div class="flex-1">
                <div class="text-[13px] font-semibold text-ink"><small class="font-normal !text-brand-70">Beber 2L agua</small> · 7 días seguidos <span class="text-ink-light">· 7/7 <i class="ti ti-check !text-[#2E7D32]"></i></span></div>
                <div class="mt-1.5 h-1.5 overflow-hidden rounded-[3px] bg-line">
                    <div class="h-full rounded-[3px] !bg-[#2E7D32] transition-[width] duration-300" style="width: 100%;"></div>
                </div>
            </div>
            <div class="flex w-20 shrink-0 flex-col items-center gap-0.5">
                <div class="text-lg leading-none">☕</div>
                <div class="text-center text-[11px] leading-tight text-ink-light">Café especial</div>
            </div>
        </div>
    </div>

    <div class="rounded-[10px] bg-white p-4 shadow-card">
        <div class="flex items-center gap-3 border-b border-line py-3.5 last:border-b-0">
            <div class="flex-1">
                <div class="text-[13px] font-semibold text-ink"><small class="font-normal !text-brand-70">Estudiar inglés</small> · 21 días seguidos <span class="text-ink-light">· 3/21</span></div>
                <div class="mt-1.5 h-1.5 overflow-hidden rounded-[3px] bg-line">
                    <div class="h-full rounded-[3px] bg-brand-50 transition-[width] duration-300" style="width: 14%;"></div>
                </div>
            </div>
            <div class="flex w-20 shrink-0 flex-col items-center gap-0.5">
                <div class="text-lg leading-none">🎮</div>
                <div class="text-center text-[11px] leading-tight text-ink-light">1 hora de videojuego</div>
            </div>
        </div>
        <div class="flex items-center gap-3 border-b border-line py-3.5 last:border-b-0">
            <div class="flex-1">
                <div class="text-[13px] font-semibold text-ink"><small class="font-normal !text-brand-70">Ejercicio</small> · 7 días seguidos <span class="text-ink-light">· 3/7</span></div>
                <div class="mt-1.5 h-1.5 overflow-hidden rounded-[3px] bg-line">
                    <div class="h-full rounded-[3px] bg-brand-50 transition-[width] duration-300" style="width: 43%;"></div>
                </div>
            </div>
            <div class="flex w-20 shrink-0 flex-col items-center justify-center gap-0.5">
                <div class="cursor-pointer text-xs !text-brand-70"><i class="ti ti-plus"></i> Añadir</div>
            </div>
        </div>
    </div>

    <h2 class="my-5 text-base font-bold text-brand-90">Tus recompensas</h2>

    <div class="rounded-[10px] bg-white p-4 shadow-card">
        <div class="mb-2 flex items-center gap-3 rounded-lg bg-page p-3 last:mb-0">
            <div class="text-xl leading-none">🍦</div>
            <div class="flex-1"><h4 class="m-0 text-[15px] font-semibold text-ink">Helado de chocolate</h4></div>
        </div>
        <div class="mb-2 flex items-center gap-3 rounded-lg bg-page p-3 last:mb-0">
            <div class="text-xl leading-none">☕</div>
            <div class="flex-1"><h4 class="m-0 text-[15px] font-semibold text-ink">Café especial</h4></div>
        </div>
        <div class="mb-2 flex items-center gap-3 rounded-lg bg-page p-3 last:mb-0">
            <div class="text-xl leading-none">🎮</div>
            <div class="flex-1"><h4 class="m-0 text-[15px] font-semibold text-ink">1 hora de videojuego</h4></div>
        </div>
    </div>

    <div class="mt-4 flex gap-3">
        <button type="button" class="inline-flex flex-1 items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-transparent text-brand-70 border-[1.5px] border-brand-70 hover:bg-brand-70 hover:text-white transition-opacity duration-200 active:scale-[0.98]" onclick="document.getElementById('goalDialog').classList.remove('hidden')"><i class="ti ti-plus"></i> Nueva meta</button>
        <button type="button" class="inline-flex flex-1 items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-transparent text-brand-70 border-[1.5px] border-brand-70 hover:bg-brand-70 hover:text-white transition-opacity duration-200 active:scale-[0.98]" onclick="document.getElementById('rewardDialog').classList.remove('hidden')"><i class="ti ti-plus"></i> Nueva recompensa</button>
    </div>
@endsection

@section('footer')
    <div id="rewardDialog" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-brand-95/50 modal-backdrop" onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="max-h-[90vh] w-[90%] max-w-[440px] overflow-y-auto rounded-[14px] bg-white p-6 shadow-[0_8px_32px_rgba(5,25,35,0.2)] modal-card">
            <span class="float-right cursor-pointer text-[22px] leading-none text-ink-light hover:text-ink" onclick="document.getElementById('rewardDialog').classList.add('hidden')">&times;</span>
            <h2 class="mb-5 text-lg font-bold text-brand-90">Nueva Recompensa</h2>
            <form method="POST" action="{{ route('reward.create') }}">
                @csrf
                <div class="mb-4">
                    <label for="rname" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Nombre</label>
                    <input type="text" id="rname" name="nombre" class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50" placeholder="Ej: Helado de chocolate">
                </div>
                <div class="mb-4">
                    <label for="rdesc" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Descripción (opcional)</label>
                    <textarea id="rdesc" name="descripcion" class="block w-full resize-y min-h-20 px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50" placeholder="¿Qué te vas a regalar?"></textarea>
                </div>
                <div class="mb-4">
                    <label for="remoji" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Emoji</label>
                    <select id="remoji" name="emoji" class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                        <option>🍦 Helado</option>
                        <option>☕ Café</option>
                        <option>🎮 Videojuego</option>
                        <option>🍕 Pizza</option>
                        <option>🎬 Película</option>
                        <option>📚 Libro</option>
                        <option>🎵 Música</option>
                        <option>🧁 Postre</option>
                        <option>🎁 Otro</option>
                    </select>
                </div>
                <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-brand-50 text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Crear Recompensa</button>
            </form>
        </div>
    </div>

    <div id="goalDialog" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-brand-95/50 modal-backdrop" onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="max-h-[90vh] w-[90%] max-w-[440px] overflow-y-auto rounded-[14px] bg-white p-6 shadow-[0_8px_32px_rgba(5,25,35,0.2)] modal-card">
            <span class="float-right cursor-pointer text-[22px] leading-none text-ink-light hover:text-ink" onclick="document.getElementById('goalDialog').classList.add('hidden')">&times;</span>
            <h2 class="mb-5 text-lg font-bold text-brand-90">Nueva Meta</h2>
            <form method="POST" action="{{ route('goals.create') }}">
                @csrf
                <div class="mb-4">
                    <label for="ghabit" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Vincular a hábito</label>
                    <select id="ghabit" name="habito_id" class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                        <option>Leer 15 minutos</option>
                        <option>Beber 2L de agua</option>
                        <option>Estudiar inglés</option>
                        <option>Ejercicio</option>
                        <option>Meditar 5 min</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="gdias" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Días requeridos</label>
                    <input type="number" id="gdias" name="racha_requerida" class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50" value="30" min="1" max="365">
                </div>
                <div class="mb-4">
                    <label for="grepeat" class="mb-1.5 block text-[13px] font-semibold text-brand-95">¿Repetible?</label>
                    <select id="grepeat" name="repetible" class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                        <option value="0">No, una sola vez</option>
                        <option value="1" selected>Sí, se puede repetir</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="greward" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Recompensa</label>
                    <select id="greward" name="recompensa_id" class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                        <option>🍦 Helado de chocolate</option>
                        <option>☕ Café especial</option>
                        <option>🎮 1 hora de videojuego</option>
                        <option value="">Sin recompensa</option>
                    </select>
                </div>
                <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-brand-50 text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Crear Meta</button>
            </form>
        </div>
    </div>

    <button type="button" class="fixed right-6 bottom-6 z-20 flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 text-[28px] text-white shadow-[0_4px_16px_rgba(0,166,251,0.35)] cursor-pointer transition-transform duration-200 active:scale-90" onclick="document.getElementById('goalDialog').classList.remove('hidden')"><i class="ti ti-plus"></i></button>

    @include('partials.bottom-nav', ['active' => 'goals'])
@endsection