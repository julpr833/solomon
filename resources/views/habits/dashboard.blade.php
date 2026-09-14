@extends('layouts.app')

@section('title', 'Solomon — Dashboard')

@section('topbar')
    <x-topbar title="Solomon">
        <x-slot:right>
            <a href="{{ route('settings') }}">
                @include('partials.avatar', ['user' => auth()->user()])
            </a>
        </x-slot:right>
    </x-topbar>
@endsection

@section('content')
    <p class="mb-4 text-sm text-ink-light">
        <span id="welcome-prefix">Bienvenido</span>, <strong class="text-ink" id="welcome-username">{{ auth()->user()?->name ?: 'Juan' }}</strong>
    </p>

    <div id="phone-warning-alert" class="mb-5 flex items-start gap-3 rounded-[10px] border-[1.5px] border-[#FFE0B2] bg-[#FFF9E6] px-4 py-3.5 shadow-card animate-fade-in" style="display: none;">
        <i class="ti ti-alert-circle mt-0.5 shrink-0 text-xl text-orange"></i>
        <div class="flex-1 text-[13.5px] leading-snug text-[#663C00]">
            No tienes un teléfono asignado, ingresa a los <a href="{{ route('settings') }}" class="font-semibold text-brand-70 underline">ajustes</a> para asignar uno y recibir recordatorios por Whatsapp!
        </div>
    </div>

    <div id="motivation-card" class="relative mb-5 rounded-[10px] bg-gradient-to-br from-brand-90 to-brand-80 p-5 text-white shadow-card transition-all duration-300 animate-fade-in" style="display: none;">
        <div class="mb-2 flex items-center justify-between">
            <span id="motivation-tag" class="mb-2.5 inline-flex items-center gap-1.5 rounded-full bg-white/15 px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wider">
                <i class="ti ti-quote"></i> <span id="motivation-type">Cargando...</span>
            </span>
            <button id="motivation-refresh" type="button" class="flex cursor-pointer items-center justify-center rounded-full border-0 bg-transparent p-1 text-lg text-white/80 transition-colors hover:bg-white/10" title="Ver otra frase">
                <i class="ti ti-rotate" id="motivation-refresh-icon"></i>
            </button>
        </div>
        <p id="motivation-quote" class="mt-1 text-base font-medium leading-relaxed">"Cargando..."</p>
        <div id="motivation-source" class="mt-2 text-xs italic text-white/70">-</div>
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-[1fr_300px]">
        <div>
            <h2 class="my-5 text-base font-bold text-brand-90">Tus hábitos</h2>

            <div class="rounded-[10px] bg-white p-4 shadow-card">
                <div class="flex cursor-pointer items-center gap-3 border-b border-line py-3.5" onclick="location.href='{{ route('habit', ['id' => 1]) }}'">
                    <div class="flex h-[22px] w-[22px] shrink-0 items-center justify-center rounded-md border-2 border-brand-50 bg-brand-50 text-sm text-white transition-colors duration-150"><i class="ti ti-check"></i></div>
                    <div class="flex-1">
                        <h3 class="text-[15px] font-semibold">Leer 15 minutos</h3>
                        <div class="mt-0.5 text-xs text-ink-light">Diario · Prioridad alta</div>
                    </div>
                    <span class="rounded-full bg-[#E3F2FD] px-2 py-0.5 text-[11px] font-semibold text-brand-80">Diario</span>
                    <span class="whitespace-nowrap text-[13px] font-semibold text-orange">12 <i class="ti ti-bolt"></i></span>
                    <div class="flex shrink-0 gap-0.5 text-ink-light">
                        <i class="ti ti-pencil cursor-pointer rounded-md p-1.5 text-[17px] transition-colors duration-150 hover:bg-page hover:text-brand-70" onclick="event.stopPropagation(); openEdit('Leer 15 minutos','Diario','Alta')"></i>
                        <i class="ti ti-trash cursor-pointer rounded-md p-1.5 text-[17px] transition-colors duration-150 hover:bg-[#FEEBEA] hover:text-danger" onclick="event.stopPropagation(); openDelete('Leer 15 minutos')"></i>
                    </div>
                </div>
                <div class="flex cursor-pointer items-center gap-3 border-b border-line py-3.5" onclick="location.href='{{ route('habit', ['id' => 2]) }}'">
                    <div class="flex h-[22px] w-[22px] shrink-0 items-center justify-center rounded-md border-2 border-brand-50 bg-brand-50 text-sm text-white transition-colors duration-150"><i class="ti ti-check"></i></div>
                    <div class="flex-1">
                        <h3 class="text-[15px] font-semibold">Beber 2L de agua</h3>
                        <div class="mt-0.5 text-xs text-ink-light">Diario · Prioridad media</div>
                    </div>
                    <span class="rounded-full bg-[#E3F2FD] px-2 py-0.5 text-[11px] font-semibold text-brand-80">Diario</span>
                    <span class="whitespace-nowrap text-[13px] font-semibold text-orange">7 <i class="ti ti-bolt"></i></span>
                    <div class="flex shrink-0 gap-0.5 text-ink-light">
                        <i class="ti ti-pencil cursor-pointer rounded-md p-1.5 text-[17px] transition-colors duration-150 hover:bg-page hover:text-brand-70" onclick="event.stopPropagation(); openEdit('Beber 2L de agua','Diario','Media')"></i>
                        <i class="ti ti-trash cursor-pointer rounded-md p-1.5 text-[17px] transition-colors duration-150 hover:bg-[#FEEBEA] hover:text-danger" onclick="event.stopPropagation(); openDelete('Beber 2L de agua')"></i>
                    </div>
                </div>
                <div class="flex cursor-pointer items-center gap-3 border-b border-line py-3.5" onclick="location.href='{{ route('habit', ['id' => 3]) }}'">
                    <div class="flex h-[22px] w-[22px] shrink-0 items-center justify-center rounded-md border-2 border-brand-70 text-sm text-white transition-colors duration-150"></div>
                    <div class="flex-1">
                        <h3 class="text-[15px] font-semibold">Estudiar inglés</h3>
                        <div class="mt-0.5 text-xs text-ink-light">Diario · Prioridad alta</div>
                    </div>
                    <span class="rounded-full bg-[#E3F2FD] px-2 py-0.5 text-[11px] font-semibold text-brand-80">Diario</span>
                    <span class="whitespace-nowrap text-[13px] font-semibold text-orange">3 <i class="ti ti-bolt"></i></span>
                    <div class="flex shrink-0 gap-0.5 text-ink-light">
                        <i class="ti ti-pencil cursor-pointer rounded-md p-1.5 text-[17px] transition-colors duration-150 hover:bg-page hover:text-brand-70" onclick="event.stopPropagation(); openEdit('Estudiar inglés','Diario','Alta')"></i>
                        <i class="ti ti-trash cursor-pointer rounded-md p-1.5 text-[17px] transition-colors duration-150 hover:bg-[#FEEBEA] hover:text-danger" onclick="event.stopPropagation(); openDelete('Estudiar inglés')"></i>
                    </div>
                </div>
                <div class="flex cursor-pointer items-center gap-3 border-b border-line py-3.5" onclick="location.href='{{ route('habit', ['id' => 4]) }}'">
                    <div class="flex h-[22px] w-[22px] shrink-0 items-center justify-center rounded-md border-2 border-brand-70 text-sm text-white transition-colors duration-150"></div>
                    <div class="flex-1">
                        <h3 class="text-[15px] font-semibold">Ejercicio</h3>
                        <div class="mt-0.5 text-xs text-ink-light">Semanal · Prioridad baja</div>
                    </div>
                    <span class="rounded-full bg-[#FFF3E0] px-2 py-0.5 text-[11px] font-semibold text-[#E65100]">Semanal</span>
                    <span class="whitespace-nowrap text-[13px] font-semibold text-orange">2 <i class="ti ti-bolt"></i></span>
                    <div class="flex shrink-0 gap-0.5 text-ink-light">
                        <i class="ti ti-pencil cursor-pointer rounded-md p-1.5 text-[17px] transition-colors duration-150 hover:bg-page hover:text-brand-70" onclick="event.stopPropagation(); openEdit('Ejercicio','Semanal','Baja')"></i>
                        <i class="ti ti-trash cursor-pointer rounded-md p-1.5 text-[17px] transition-colors duration-150 hover:bg-[#FEEBEA] hover:text-danger" onclick="event.stopPropagation(); openDelete('Ejercicio')"></i>
                    </div>
                </div>
                <div class="flex cursor-pointer items-center gap-3 py-3.5" onclick="location.href='{{ route('habit', ['id' => 5]) }}'">
                    <div class="flex h-[22px] w-[22px] shrink-0 items-center justify-center rounded-md border-2 border-brand-70 text-sm text-white transition-colors duration-150"></div>
                    <div class="flex-1">
                        <h3 class="text-[15px] font-semibold">Meditar 5 min</h3>
                        <div class="mt-0.5 text-xs text-ink-light">Diario · Prioridad media</div>
                    </div>
                    <span class="rounded-full bg-[#E3F2FD] px-2 py-0.5 text-[11px] font-semibold text-brand-80">Diario</span>
                    <span class="whitespace-nowrap text-[13px] font-semibold text-orange">1 <i class="ti ti-bolt"></i></span>
                    <div class="flex shrink-0 gap-0.5 text-ink-light">
                        <i class="ti ti-pencil cursor-pointer rounded-md p-1.5 text-[17px] transition-colors duration-150 hover:bg-page hover:text-brand-70" onclick="event.stopPropagation(); openEdit('Meditar 5 min','Diario','Media')"></i>
                        <i class="ti ti-trash cursor-pointer rounded-md p-1.5 text-[17px] transition-colors duration-150 hover:bg-[#FEEBEA] hover:text-danger" onclick="event.stopPropagation(); openDelete('Meditar 5 min')"></i>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="mb-5 grid grid-cols-1 gap-3">
                <div class="rounded-[10px] bg-white p-3.5 text-center shadow-card">
                    <div class="text-[26px] font-bold leading-tight text-brand-80">3/5</div>
                    <div class="mt-0.5 text-xs text-ink-light">Completado hoy</div>
                </div>
                <div class="rounded-[10px] bg-white p-3.5 text-center shadow-card">
                    <div class="text-[26px] font-bold leading-tight text-orange">7 <i class="ti ti-bolt"></i></div>
                    <div class="mt-0.5 text-xs text-ink-light">Racha actual</div>
                </div>
                <div class="rounded-[10px] bg-white p-3.5 text-center shadow-card">
                    <div class="text-[26px] font-bold leading-tight text-brand-80">64%</div>
                    <div class="mt-0.5 text-xs text-ink-light">Esta semana</div>
                </div>
            </div>

            <h2 class="mt-1 mb-5 text-base font-bold text-brand-90">Progreso rápido</h2>
            <div class="rounded-[10px] bg-white p-4 shadow-card">
                <div class="flex flex-col gap-[3px] py-1">
                    <div class="flex gap-[3px]"><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#38BDF8]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#38BDF8]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#38BDF8]"></div></div>
                    <div class="flex gap-[3px]"><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#38BDF8]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#38BDF8]"></div></div>
                    <div class="flex gap-[3px]"><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#38BDF8]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div></div>
                    <div class="flex gap-[3px]"><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#38BDF8]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div></div>
                    <div class="flex gap-[3px]"><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#38BDF8]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#38BDF8]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#38BDF8]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#38BDF8]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div></div>
                    <div class="flex gap-[3px]"><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#38BDF8]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#38BDF8]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#7DD3FC]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#38BDF8]"></div></div>
                    <div class="flex gap-[3px]"><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-line"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#38BDF8]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#38BDF8]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#BAE6FD]"></div><div class="h-[14px] w-[14px] flex-1 shrink-0 rounded-[2px] bg-[#0284C7]"></div></div>
                </div>
                <p class="mt-2 text-[11px] text-ink-light">Últimas 4 semanas</p>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <button type="button" class="fixed right-6 bottom-6 z-20 flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 text-[28px] text-white shadow-[0_4px_16px_rgba(0,166,251,0.35)] cursor-pointer transition-transform duration-200 active:scale-90" onclick="document.getElementById('habitDialog').classList.add('open')"><i class="ti ti-plus"></i></button>

    <div id="habitDialog" class="fixed inset-0 z-[100] hidden items-center justify-center bg-brand-95/50" onclick="if(event.target===this)this.classList.remove('open')">
        <div class="max-h-[90vh] w-[90%] max-w-[440px] overflow-y-auto rounded-[14px] bg-white p-6 shadow-[0_8px_32px_rgba(5,25,35,0.2)]">
            <span class="float-right cursor-pointer text-[22px] leading-none text-ink-light hover:text-ink" onclick="document.getElementById('habitDialog').classList.remove('open')">&times;</span>
            <h2 class="mb-5 text-lg font-bold text-brand-90">Nuevo Hábito</h2>
            <form method="POST" action="{{ route('habit.create') }}">
                @csrf
                <div class="mb-4">
                    <label for="hname" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Nombre del hábito</label>
                    <input type="text" id="hname" name="objetivo" class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50" placeholder="Ej: Leer 15 minutos">
                </div>
                <div class="mb-4">
                    <label for="hdesc" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Descripción (opcional)</label>
                    <textarea id="hdesc" name="descripcion" class="block w-full resize-y min-h-20 px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50" placeholder="¿Por qué quieres crear este hábito?"></textarea>
                </div>
                <div class="mb-4">
                    <label for="hfreq" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Frecuencia</label>
                    <select id="hfreq" name="frecuencia" class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                        <option value="Pluridiaria">Varias veces al día</option>
                        <option value="Daria" selected>Diario</option>
                        <option value="Semanal">Semanal</option>
                        <option value="Mensual">Mensual</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="hpriority" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Prioridad</label>
                    <select id="hpriority" name="prioridad" class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                        <option value="Alta">Alta</option>
                        <option value="Media" selected>Media</option>
                        <option value="Baja">Baja</option>
                    </select>
                </div>
                <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-brand-50 text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Crear Hábito</button>
            </form>
        </div>
    </div>

    <div id="editDialog" class="fixed inset-0 z-[100] hidden items-center justify-center bg-brand-95/50" onclick="if(event.target===this)this.classList.remove('open')">
        <div class="max-h-[90vh] w-[90%] max-w-[440px] overflow-y-auto rounded-[14px] bg-white p-6 shadow-[0_8px_32px_rgba(5,25,35,0.2)]">
            <span class="float-right cursor-pointer text-[22px] leading-none text-ink-light hover:text-ink" onclick="document.getElementById('editDialog').classList.remove('open')">&times;</span>
            <h2 class="mb-5 text-lg font-bold text-brand-90">Editar Hábito</h2>
            <form method="POST" action="{{ route('habit.edit') }}">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="e-name" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Nombre del hábito</label>
                    <input type="text" id="e-name" name="objetivo" class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50" placeholder="Ej: Leer 15 minutos">
                </div>
                <div class="mb-4">
                    <label for="e-desc" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Descripción (opcional)</label>
                    <textarea id="e-desc" name="descripcion" class="block w-full resize-y min-h-20 px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50" placeholder="¿Por qué quieres mantener este hábito?"></textarea>
                </div>
                <div class="mb-4">
                    <label for="e-freq" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Frecuencia</label>
                    <select id="e-freq" name="frecuencia" class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                        <option value="Pluridiaria">Varias veces al día</option>
                        <option value="Daria">Diario</option>
                        <option value="Semanal">Semanal</option>
                        <option value="Mensual">Mensual</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="e-priority" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Prioridad</label>
                    <select id="e-priority" name="prioridad" class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                        <option value="Alta">Alta</option>
                        <option value="Media">Media</option>
                        <option value="Baja">Baja</option>
                    </select>
                </div>
                <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-brand-50 text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Guardar cambios</button>
            </form>
        </div>
    </div>

    <div id="deleteDialog" class="fixed inset-0 z-[100] hidden items-center justify-center bg-brand-95/50" onclick="if(event.target===this)this.classList.remove('open')">
        <div class="max-h-[90vh] w-[90%] max-w-[440px] overflow-y-auto rounded-[14px] bg-white p-6 shadow-[0_8px_32px_rgba(5,25,35,0.2)]">
            <span class="float-right cursor-pointer text-[22px] leading-none text-ink-light hover:text-ink" onclick="document.getElementById('deleteDialog').classList.remove('open')">&times;</span>
            <h2 class="mb-5 text-lg font-bold text-brand-90">Eliminar Hábito</h2>
            <p class="mb-5 text-sm text-ink-light">¿Estás seguro de que querés eliminar el hábito <strong class="text-ink" id="delete-name">""</strong>? Se perderá todo su historial y progreso.</p>
            <div class="flex gap-3">
                <button type="button" class="inline-flex flex-1 items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-transparent text-brand-70 border-[1.5px] border-brand-70 hover:bg-brand-70 hover:text-white transition-opacity duration-200 active:scale-[0.98]" onclick="document.getElementById('deleteDialog').classList.remove('open')">Cancelar</button>
                <form method="POST" action="{{ route('habit.delete') }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-danger text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Eliminar</button>
                </form>
            </div>
        </div>
    </div>

    @include('partials.bottom-nav', ['active' => 'habits'])
@endsection

@section('scripts')
    <script>
        function openEdit(name, freq, priority) {
            document.getElementById('e-name').value = name;
            document.getElementById('e-freq').value = freq;
            document.getElementById('e-priority').value = priority;
            document.getElementById('editDialog').classList.add('open');
        }
        function openDelete(name) {
            document.getElementById('delete-name').textContent = '"' + name + '"';
            document.getElementById('deleteDialog').classList.add('open');
        }

        const proverbs = [
            { text: "La blanda respuesta quita la ira; mas la palabra áspera hace subir el furor.", ref: "Proverbios 15:1" },
            { text: "El que guarda su boca guarda su alma; mas el que mucho abre sus labios tendrá calamidad.", ref: "Proverbios 13:3" },
            { text: "Encomienda a Jehová tus obras, y tus pensamientos serán afirmados.", ref: "Proverbios 16:3" },
            { text: "El corazón alegre hermosea el rostro; mas por el dolor del corazón el espíritu se abate.", ref: "Proverbios 15:13" },
            { text: "Mejor es lo poco con el temor de Jehová, que el gran tesoro donde hay turbación.", ref: "Proverbios 15:16" },
            { text: "El que camina en integridad anda seguro; mas el que pervierte sus caminos será quebrantado.", ref: "Proverbios 10:9" },
            { text: "El corazón del hombre traza su rumbo, pero sus pasos los dirige el Señor.", ref: "Proverbios 16:9" },
            { text: "Como agua fría al alma sedienta, así son las buenas nuevas de lejanas tierras.", ref: "Proverbios 25:25" }
        ];

        const motivationalQuotes = [
            { text: "La única forma de hacer un gran trabajo es amar lo que haces.", ref: "Steve Jobs" },
            { text: "No juzgues cada día por la cosecha que recoges, sino por las semillas que plantas.", ref: "Robert Louis Stevenson" },
            { text: "La motivación es lo que te pone en marcha. El hábito es lo que hace que sigas.", ref: "Jim Ryun" },
            { text: "El éxito no es la clave de la felicidad. La felicidad es la clave del éxito.", ref: "Albert Schweitzer" },
            { text: "Los pequeños hábitos diarios son los que construyen los grandes resultados.", ref: "Anónimo" },
            { text: "Cree que puedes y casi habrás llegado.", ref: "Theodore Roosevelt" },
            { text: "No cuentes los días, haz que los días cuenten.", ref: "Muhammad Ali" },
            { text: "La disciplina es el puente entre las metas y los logros.", ref: "Jim Rohn" }
        ];

        const motivationDisabledClasses = [
            'bg-white', 'border-2', 'border-dashed', 'border-line', 'shadow-none', 'text-ink-light'
        ];

        function setMotivationDisabled(card, tag, quote, source, disabled) {
            motivationDisabledClasses.forEach(c => card.classList.toggle(c, disabled));
            if (tag) {
                tag.classList.toggle('bg-page', disabled);
                tag.classList.toggle('text-ink-light', disabled);
            }
            if (quote) {
                quote.classList.toggle('italic', disabled);
                quote.classList.toggle('text-sm', disabled);
            }
            if (source && disabled) source.textContent = '';
        }

        function initMotivation() {
            const card = document.getElementById('motivation-card');
            const tag = document.getElementById('motivation-tag');
            const typeEl = document.getElementById('motivation-type');
            const quoteEl = document.getElementById('motivation-quote');
            const sourceEl = document.getElementById('motivation-source');
            const refreshBtn = document.getElementById('motivation-refresh');
            const refreshIcon = document.getElementById('motivation-refresh-icon');

            if (!card) return;

            const provSaved = localStorage.getItem('proverbiosBiblicos');
            const quoteSaved = localStorage.getItem('frasesMotivacionales');

            const showProverbs = provSaved === null ? true : provSaved === 'true';
            const showQuotes = quoteSaved === null ? true : quoteSaved === 'true';

            if (!showProverbs && !showQuotes) {
                setMotivationDisabled(card, tag, quoteEl, sourceEl, true);
                card.style.display = 'block';
                if (tag) tag.style.display = 'none';
                if (refreshBtn) refreshBtn.style.display = 'none';
                quoteEl.innerHTML = '<i class="ti ti-info-circle" style="vertical-align: middle; margin-right: 4px;"></i> Contenido motivacional desactivado. Habilítalo en <a href="{{ route('settings') }}" style="font-weight: 600; text-decoration: underline; color: var(--color-brand-70);">Ajustes</a> para inspirar tu día.';
                return;
            }

            setMotivationDisabled(card, tag, quoteEl, sourceEl, false);
            if (tag) tag.style.display = 'inline-flex';
            if (refreshBtn) refreshBtn.style.display = 'flex';
            card.style.display = 'block';

            let pool = [];
            if (showProverbs) pool = pool.concat(proverbs.map(p => ({ ...p, type: 'Proverbio Bíblico', icon: 'ti-book' })));
            if (showQuotes) pool = pool.concat(motivationalQuotes.map(q => ({ ...q, type: 'Frase Motivacional', icon: 'ti-quote' })));

            if (pool.length === 0) return;

            const currentQuote = quoteEl.textContent.replace(/"/g, '');
            let selected;
            let attempts = 0;
            do {
                selected = pool[Math.floor(Math.random() * pool.length)];
                attempts++;
            } while (selected.text === currentQuote && pool.length > 1 && attempts < 10);

            card.classList.remove('animate-fade-in');
            void card.offsetWidth;
            card.classList.add('animate-fade-in');

            if (typeEl) {
                typeEl.textContent = selected.type;
                const iconEl = tag.querySelector('i');
                if (iconEl) iconEl.className = 'ti ' + selected.icon;
            }
            quoteEl.textContent = `"${selected.text}"`;
            sourceEl.textContent = selected.ref;

            if (refreshBtn) {
                refreshBtn.onclick = () => {
                    if (refreshIcon) {
                        refreshIcon.classList.remove('animate-spin');
                        void refreshIcon.offsetWidth;
                        refreshIcon.classList.add('animate-spin');
                    }
                    initMotivation();
                };
            }
        }

        function initWelcomeGreeting() {
            const prefixEl = document.getElementById('welcome-prefix');
            const usernameEl = document.getElementById('welcome-username');
            const savedName = localStorage.getItem('userName');
            const savedGender = localStorage.getItem('userGender');

            if (usernameEl && savedName && savedName.trim() !== '') usernameEl.textContent = savedName;
            if (prefixEl && savedGender === 'femenino') prefixEl.textContent = 'Bienvenida';
            else if (prefixEl) prefixEl.textContent = 'Bienvenido';
        }

        function checkPhoneNumberWarning() {
            const alertCard = document.getElementById('phone-warning-alert');
            if (!alertCard) return;
            const savedPhone = localStorage.getItem('whatsappPhone');
            alertCard.style.display = (!savedPhone || savedPhone.trim() === '') ? 'flex' : 'none';
        }

        document.addEventListener('DOMContentLoaded', () => {
            initMotivation();
            checkPhoneNumberWarning();
            initWelcomeGreeting();
        });
    </script>
@endsection