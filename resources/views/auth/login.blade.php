@extends('layouts.auth')

@section('title', 'Solomon — Iniciar Sesión')

@section('content')
    <div class="mb-8 text-center">
        <img src="{{ asset('logo-base.png') }}" alt="Solomon" class="mx-auto mb-3 w-16 h-16 object-contain">
        <h1 class="text-[32px] font-extrabold text-brand-90 tracking-[-0.5px] leading-tight">Solomon</h1>
        <span class="mt-1 block text-xs text-ink-light">Tus hábitos, tu crecimiento</span>
    </div>

    <div class="rounded-[10px] bg-white p-4 shadow-card">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Correo electrónico</label>
                <input type="email" id="email" name="email"
                    class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                    placeholder="Correo electrónico..." value="{{ old('email') }}" required>
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Contraseña</label>
                <input type="password" id="password" name="password"
                    class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                    placeholder="Contraseña" required>
                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-brand-50 text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Iniciar
                Sesión</button>
        </form>
    </div>

    <div class="mt-5 text-center text-sm text-ink-light">
        ¿No tienes cuenta? <a href="{{ route('signup') }}" class="font-semibold">Regístrate</a>
    </div>
@endsection