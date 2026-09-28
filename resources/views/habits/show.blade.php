@extends('layouts.app')

@section('title', 'Solomon — Leer 15 minutos')

@php
    $levels = [];
    $seed = 2026;
    $cellClass = ['bg-[#BAE6FD]', 'bg-[#7DD3FC]', 'bg-[#38BDF8]', 'bg-[#0284C7]'];
    for ($r = 0; $r < 8; $r++) {
        $row = [];
        for ($c = 0; $c < 53; $c++) {
            $seed = ($seed * 1103515245 + 12345) & 0x7fffffff;
            $row[] = ($seed % 100) < 48 ? 0 : (($seed >> 3) % 4) + 1;
        }
        $levels[] = $row;
    }
@endphp

@section('topbar')
    <x-topbar title="Leer 15 minutos" back="{{ route('dashboard') }}"></x-topbar>
@endsection

@section('content')
    <div class="mb-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
        <div class="rounded-[10px] bg-white p-3.5 text-center shadow-card">
            <div class="text-[26px] font-bold leading-tight !text-orange">12 <i class="ti ti-bolt"></i></div>
            <div class="mt-0.5 text-xs text-ink-light">Racha actual</div>
        </div>
        <div class="rounded-[10px] bg-white p-3.5 text-center shadow-card">
            <div class="text-[26px] font-bold leading-tight text-brand-80">42</div>
            <div class="mt-0.5 text-xs text-ink-light">Record racha</div>
        </div>
        <div class="rounded-[10px] bg-white p-3.5 text-center shadow-card">
            <div class="text-[26px] font-bold leading-tight text-brand-80">87</div>
            <div class="mt-0.5 text-xs text-ink-light">Completado</div>
        </div>
        <div class="rounded-[10px] bg-white p-3.5 text-center shadow-card">
            <div class="text-[26px] font-bold leading-tight text-brand-80">12</div>
            <div class="mt-0.5 text-xs text-ink-light">Fallos</div>
        </div>
    </div>

    <button type="button"
        class="mb-3 inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-brand-50 text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]"><i
            class="ti ti-check"></i> Marcar como completado hoy</button>

    <div class="mb-5 flex gap-3">
        <button type="button"
            class="inline-flex flex-1 items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-transparent text-brand-70 border-[1.5px] border-brand-70 hover:bg-brand-70 hover:text-white transition-opacity duration-200 active:scale-[0.98]"
            onclick="document.getElementById('editDialog').classList.remove('hidden')"><i class="ti ti-edit"></i> Editar
            hábito</button>
        <button type="button"
            class="inline-flex flex-1 items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-transparent !text-danger border-[1.5px] !border-danger hover:bg-danger hover:text-white transition-opacity duration-200 active:scale-[0.98]"
            onclick="document.getElementById('deleteDialog').classList.remove('hidden')"><i class="ti ti-trash"></i>
            Eliminar</button>
    </div>

    <div class="mb-4 rounded-[10px] bg-white p-4 shadow-card">
        <h3 class="mb-3 text-[15px] font-semibold !text-brand-90">Progreso anual</h3>
        <div class="overflow-x-auto">
            <div class="inline-flex flex-col gap-1 py-1">
                @foreach ($levels as $row)
                    <div class="flex gap-[3px]">
                        @foreach ($row as $cell)
                            <div class="h-5 w-5 shrink-0 rounded-[3px] {{ $cell ? $cellClass[$cell - 1] : 'bg-line' }}"></div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <h2 class="my-5 text-base font-bold text-brand-90">Metas vinculadas</h2>
    <div class="rounded-[10px] bg-white p-4 shadow-card">
        <div class="flex items-center gap-3 border-b border-line py-3.5 last:border-b-0">
            <div class="flex-1">
                <div class="text-[13px] font-semibold text-ink">30 días seguidos <span class="text-ink-light">· 12/30</span>
                </div>
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
                <div class="text-[13px] font-semibold text-ink">7 días seguidos <span class="text-ink-light">· 7/7 <i
                            class="ti ti-check !text-[#2E7D32]"></i></span></div>
                <div class="mt-1.5 h-1.5 overflow-hidden rounded-[3px] bg-line">
                    <div class="h-full rounded-[3px] !bg-[#2E7D32] transition-[width] duration-300" style="width: 100%;">
                    </div>
                </div>
            </div>
            <div class="flex w-20 shrink-0 flex-col items-center gap-0.5">
                <div class="text-lg leading-none">☕</div>
                <div class="text-center text-[11px] leading-tight text-ink-light">Café especial</div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <div id="editDialog" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-brand-95/50 modal-backdrop"
        onclick="if(event.target===this)this.classList.add('hidden')">
        <div
            class="max-h-[90vh] w-[90%] max-w-[440px] overflow-y-auto rounded-[14px] bg-white p-6 shadow-[0_8px_32px_rgba(5,25,35,0.2)] modal-card">
            <span class="float-right cursor-pointer text-[22px] leading-none text-ink-light hover:text-ink"
                onclick="document.getElementById('editDialog').classList.add('hidden')">&times;</span>
            <h2 class="mb-5 text-lg font-bold text-brand-90">Editar Hábito</h2>
            <form method="POST" action="{{ route('habit.edit') }}">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="e-name" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Nombre del
                        hábito</label>
                    <input type="text" id="e-name" name="objetivo"
                        class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                        value="Leer 15 minutos">
                </div>
                <div class="mb-4">
                    <label for="e-desc" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Descripción
                        (opcional)</label>
                    <textarea id="e-desc" name="descripcion"
                        class="block w-full resize-y min-h-20 px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                        placeholder="¿Por qué quieres mantener este hábito?"></textarea>
                </div>
                <div class="mb-4">
                    <label for="e-freq" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Frecuencia</label>
                    <select id="e-freq" name="frecuencia"
                        class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                        <option value="Pluridiaria">Varias veces al día</option>
                        <option value="Daria" selected>Diario</option>
                        <option value="Semanal">Semanal</option>
                        <option value="Mensual">Mensual</option>
                        <option value="Anual">Anual</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="e-priority" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Prioridad</label>
                    <select id="e-priority" name="prioridad"
                        class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                        <option value="Alta" selected>Alta</option>
                        <option value="Media">Media</option>
                        <option value="Baja">Baja</option>
                    </select>
                </div>
                <button type="submit"
                    class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-brand-50 text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Guardar
                    cambios</button>
            </form>
        </div>
    </div>

    <div id="deleteDialog"
        class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-brand-95/50 modal-backdrop"
        onclick="if(event.target===this)this.classList.add('hidden')">
        <div
            class="max-h-[90vh] w-[90%] max-w-[440px] overflow-y-auto rounded-[14px] bg-white p-6 shadow-[0_8px_32px_rgba(5,25,35,0.2)] modal-card">
            <span class="float-right cursor-pointer text-[22px] leading-none text-ink-light hover:text-ink"
                onclick="document.getElementById('deleteDialog').classList.add('hidden')">&times;</span>
            <h2 class="mb-5 text-lg font-bold text-brand-90">Eliminar Hábito</h2>
            <p class="mb-5 text-sm text-ink-light">¿Estás seguro de que querés eliminar el hábito <strong
                    class="text-ink">"Leer 15 minutos"</strong>? Se perderá todo tu historial y progreso.</p>
            <div class="flex gap-3">
                <button type="button"
                    class="inline-flex flex-1 items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-transparent text-brand-70 border-[1.5px] border-brand-70 hover:bg-brand-70 hover:text-white transition-opacity duration-200 active:scale-[0.98]"
                    onclick="document.getElementById('deleteDialog').classList.add('hidden')">Cancelar</button>
                <form method="POST" action="{{ route('habit.delete') }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-danger text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Eliminar</button>
                </form>
            </div>
        </div>
    </div>

    @include('partials.bottom-nav', ['active' => 'habits'])
@endsection