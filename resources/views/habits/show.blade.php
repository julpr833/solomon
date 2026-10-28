@extends('layouts.app')

@section('title', 'Solomon — ' . $habito->Objetivo)

@php
    $heatLevels = ['#EDF2F7', '#BAE6FD', '#7DD3FC', '#38BDF8', '#0284C7'];
    $rachaActual = $habito->getCurrentRacha();
    $maxRacha = (int) ($habito->max_racha ?? 0);
    $completados = (int) $habito->cumplimientos_count;
    $fallos = (int) $habito->fallos_count;
@endphp

@section('topbar')
    <x-topbar :title="$habito->Objetivo" back="{{ route('dashboard') }}"></x-topbar>
@endsection

@section('content')
    <div class="mb-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
        <div class="rounded-[10px] bg-white p-3.5 text-center shadow-card">
            <div class="text-[26px] font-bold leading-tight !text-orange">{{ $rachaActual }} <i class="ti ti-bolt"></i>
            </div>
            <div class="mt-0.5 text-xs text-ink-light">Racha actual</div>
        </div>
        <div class="rounded-[10px] bg-white p-3.5 text-center shadow-card">
            <div class="text-[26px] font-bold leading-tight text-brand-80">{{ $maxRacha }}</div>
            <div class="mt-0.5 text-xs text-ink-light">Record racha</div>
        </div>
        <div class="rounded-[10px] bg-white p-3.5 text-center shadow-card">
            <div class="text-[26px] font-bold leading-tight text-brand-80">{{ $completados }}</div>
            <div class="mt-0.5 text-xs text-ink-light">Completado</div>
        </div>
        <div class="rounded-[10px] bg-white p-3.5 text-center shadow-card">
            <div class="text-[26px] font-bold leading-tight text-brand-80">{{ $fallos }}</div>
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
        <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
            <div>
                <h3 class="text-[15px] font-semibold !text-brand-90">Progreso anual</h3>
                <p class="mt-0.5 text-[11px] text-ink-light">Últimas 53 semanas · {{ $heatmap['start']->format('d/m/Y') }} — hoy</p>
            </div>
            <div class="flex items-center gap-1 text-[10px] text-ink-light">
                <span>Menos</span>
                @foreach ($heatLevels as $color)
                    <span class="h-2.5 w-2.5 rounded-[2px]" style="background-color: {{ $color }}"></span>
                @endforeach
                <span>Más</span>
            </div>
        </div>
        <div class="overflow-x-auto pb-1">
            <div class="grid gap-[2px]"
                style="grid-template-columns: repeat({{ count($heatmap['weeks']) }}, 14px); grid-template-rows: 14px repeat(7, 14px);">
                @for ($week = 0; $week < count($heatmap['weeks']); $week++)
                    <div class="whitespace-nowrap text-[9px] font-semibold uppercase leading-[14px] tracking-wide text-ink-light">
                        {{ $heatmap['months'][$week] ?? '' }}
                    </div>
                @endfor
                @for ($day = 0; $day < 7; $day++)
                    @for ($week = 0; $week < count($heatmap['weeks']); $week++)
                        @php
                            $cell = $heatmap['weeks'][$week][$day];
                            $level = $cell['count'] > 0 ? min(4, $cell['count']) : 0;
                            $color = $cell['future'] ? 'rgba(208, 216, 226, 0.35)' : $heatLevels[$level];
                            $label = $cell['future'] ? '' : $cell['date']->format('d/m/Y') . ' · ' . $cell['count'] . ($cell['count'] === 1 ? ' cumplimiento' : ' cumplimientos');
                        @endphp
                        <div class="h-3.5 w-3.5 rounded-[3px] transition-transform duration-150 hover:scale-125 {{ $cell['today'] ? 'ring-2 ring-brand-50 ring-offset-1' : '' }}"
                            style="background-color: {{ $color }}" title="{{ $label }}"></div>
                    @endfor
                @endfor
            </div>
        </div>
    </div>

    <h2 class="my-5 text-base font-bold text-brand-90">Metas vinculadas</h2>
    <div class="rounded-[10px] bg-white p-4 shadow-card">
        @forelse ($habito->metas->sortBy('RachaRequerida') as $meta)
            @php
                $requerida = (int) $meta->RachaRequerida;
                $progreso = $requerida > 0 ? min(100, (int) round($rachaActual / $requerida * 100)) : 0;
                $cumplida = $rachaActual >= $requerida;
                $recompensa = $meta->recompensas->first();
            @endphp
            <div class="flex items-center gap-3 border-b border-line py-3.5 last:border-b-0">
                <div class="flex-1">
                    <div class="text-[13px] font-semibold text-ink">{{ $requerida }} días seguidos <span
                            class="text-ink-light">· {{ $rachaActual }}/{{ $requerida }}@if ($cumplida)
                                <i class="ti ti-check !text-[#2E7D32]"></i>
                            @endif
                        </span></div>
                    <div class="mt-1.5 h-1.5 overflow-hidden rounded-[3px] bg-line">
                        <div class="h-full rounded-[3px] {{ $cumplida ? '!bg-[#2E7D32]' : 'bg-brand-50' }} transition-[width] duration-300"
                            style="width: {{ $progreso }}%;"></div>
                    </div>
                </div>
                <div class="flex w-20 shrink-0 flex-col items-center gap-0.5">
                    @if ($recompensa)
                        <div class="text-lg leading-none">{{ $recompensa->Emoji }}</div>
                        <div class="text-center text-[11px] leading-tight text-ink-light">{{ $recompensa->Nombre }}</div>
                    @else
                        <div class="text-center text-[11px] leading-tight text-ink-light">Sin recompensa</div>
                    @endif
                </div>
            </div>
        @empty
            <p class="py-3 text-center text-sm text-ink-light">Este hábito todavía no tiene metas.</p>
        @endforelse
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
                <input type="hidden" name="habito_id" value="{{ $habito->ID_Habito }}">
                <div class="mb-4">
                    <label for="e-name" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Nombre del
                        hábito</label>
                    <input type="text" id="e-name" name="objetivo" value="{{ $habito->Objetivo }}"
                        class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                        maxlength="45" required>
                </div>
                <div class="mb-4">
                    <label for="e-desc" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Descripción
                        (opcional)</label>
                    <textarea id="e-desc" name="descripcion" maxlength="300"
                        class="block w-full resize-y min-h-20 px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                        placeholder="¿Por qué quieres mantener este hábito?">{{ $habito->Descripcion }}</textarea>
                </div>
                <div class="mb-4">
                    <label for="e-freq" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Frecuencia</label>
                    <select id="e-freq" name="frecuencia"
                        class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                        {{-- <option value="Pluridiaria">Varias veces al día</option> --}}
                        <option value="Daria" @selected($habito->Frecuencia === 'Daria')>Diario</option>
                        <option value="Semanal" @selected($habito->Frecuencia === 'Semanal')>Semanal</option>
                        <option value="Mensual" @selected($habito->Frecuencia === 'Mensual')>Mensual</option>
                        <option value="Anual" @selected($habito->Frecuencia === 'Anual')>Anual</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="e-reminder" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Hora del recordatorio
                        (opcional)</label>
                    <input type="time" id="e-reminder" name="hora_recordatorio"
                        value="{{ $habito->recordatorio?->ProximaFecha?->format('H:i') }}"
                        class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                    <p class="mt-1 text-xs text-ink-light">Se enviará por WhatsApp según la frecuencia del hábito.</p>
                </div>
                <div class="mb-4">
                    <label for="e-priority" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Prioridad</label>
                    <select id="e-priority" name="prioridad"
                        class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                        <option value="Alta" @selected($habito->Prioridad === 'Alta')>Alta</option>
                        <option value="Media" @selected($habito->Prioridad === 'Media')>Media</option>
                        <option value="Baja" @selected($habito->Prioridad === 'Baja')>Baja</option>
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
                    class="text-ink">"{{ $habito->Objetivo }}"</strong>? Se perderá todo tu historial y progreso.</p>
            <div class="flex gap-3">
                <button type="button"
                    class="inline-flex flex-1 items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-transparent text-brand-70 border-[1.5px] border-brand-70 hover:bg-brand-70 hover:text-white transition-opacity duration-200 active:scale-[0.98]"
                    onclick="document.getElementById('deleteDialog').classList.add('hidden')">Cancelar</button>
                <form method="POST" action="{{ route('habit.delete') }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="habito_id" value="{{ $habito->ID_Habito }}">
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-danger text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Eliminar</button>
                </form>
            </div>
        </div>
    </div>

    @include('partials.bottom-nav', ['active' => 'habits'])
@endsection
