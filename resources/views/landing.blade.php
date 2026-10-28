@extends('layouts.app')

@section('title', 'Solomon — Tu app de hábitos')

@section('content')
    <div class="pt-10 sm:pt-14 pb-10 text-center">
        <img src="{{ asset('logo-base.png') }}" alt="Solomon" class="mx-auto mb-3 w-20 h-20 object-contain">

        <h1 class="my-5 text-[42px] font-extrabold text-brand-90 tracking-tight leading-tight">Solomon</h1>

        <p class="mx-auto mb-8 max-w-[520px] text-lg text-ink-light">
            Construí hábitos que duran. Hacé seguimiento de tu progreso, establecé metas y recompensate por cada logro.
        </p>

        <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ route('signup') }}" class="inline-flex items-center justify-center px-9 py-3.5 rounded-lg font-semibold text-[15px] cursor-pointer bg-brand-50 text-white hover:opacity-90 transition-opacity duration-200">
                Crear cuenta
            </a>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-9 py-3.5 rounded-lg font-semibold text-[15px] cursor-pointer bg-transparent text-brand-70 border-[1.5px] border-brand-70 hover:bg-brand-70 hover:text-white transition-opacity duration-200">
                Iniciar sesión
            </a>
        </div>
    </div>

    <div class="my-12 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-[10px] bg-white p-6 text-center shadow-card">
            <div class="mb-3 text-4xl text-brand-50"><i class="ti ti-list-check"></i></div>
            <h3 class="mb-1.5 text-base font-bold text-brand-90">Seguí tus hábitos</h3>
            <p class="text-[13px] text-ink-light">Registrá tu progreso diario y mantené el enfoque en lo que importa.</p>
        </div>
        <div class="rounded-[10px] bg-white p-6 text-center shadow-card">
            <div class="mb-3 text-4xl text-brand-50"><i class="ti ti-trophy"></i></div>
            <h3 class="mb-1.5 text-base font-bold text-brand-90">Metas y recompensas</h3>
            <p class="text-[13px] text-ink-light">Establecé objetivos y celebra cada logro con recompensas personalizadas.</p>
        </div>
        <div class="rounded-[10px] bg-white p-6 text-center shadow-card">
            <div class="mb-3 text-4xl text-brand-50"><i class="ti ti-calendar-stats"></i></div>
            <h3 class="mb-1.5 text-base font-bold text-brand-90">Progreso visual</h3>
            <p class="text-[13px] text-ink-light">Mirá tu evolución con gráficos anuales y estadísticas claras.</p>
        </div>
        <div class="rounded-[10px] bg-white p-6 text-center shadow-card">
            <div class="mb-3 text-4xl text-brand-50"><i class="ti ti-bolt"></i></div>
            <h3 class="mb-1.5 text-base font-bold text-brand-90">Rachas motivacionales</h3>
            <p class="text-[13px] text-ink-light">No pierdas el ritmo. Cada día seguido suma a tu racha personal.</p>
        </div>
    </div>
@endsection

@section('footer')
    <div class="border-t border-line py-8 text-center text-[13px] text-ink-light">
        <p>Solomon — app de hábitos personal</p>
    </div>
@endsection