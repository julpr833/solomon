@extends('layouts.auth')

@section('title', 'Solomon — Crear Cuenta')

@section('content')
    <div class="mb-8 text-center">
        <img src="{{ asset('logo-base.png') }}" alt="Solomon" class="mx-auto mb-3 w-16 h-16 object-contain">
        <h1 class="text-[32px] font-extrabold text-brand-90 tracking-[-0.5px] leading-tight">Solomon</h1>
        <span class="mt-1 block text-xs text-ink-light">Crea tu cuenta</span>
    </div>

    <div class="rounded-[10px] bg-white p-4 shadow-card">
        <form method="POST" action="{{ route('signup') }}">
            @csrf
            <div class="mb-4">
                <label for="name" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Nombre de usuario</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                    placeholder="Tu nombre de usuario" required>
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="gender" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Sexo</label>
                <select id="gender" name="gender"
                    class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                    required>
                    <option value="" disabled selected>Selecciona tu sexo</option>
                    <option value="M" @selected(old('gender') === 'M')>Masculino</option>
                    <option value="F" @selected(old('gender') === 'F')>Femenino</option>
                </select>
                @error('gender')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="email" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Correo electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                    placeholder="tu@correo.com" required>
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="telefono" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Teléfono
                    (opcional)</label>
                <input type="text" id="telefono" name="telefono"
                    class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                    placeholder="Ej: +54 11 1234-5678" value="{{ old('telefono') }}">
                @error('telefono')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Contraseña</label>
                <input type="password" id="password" name="password"
                    class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                    placeholder="••••••••" required>
                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="confirm" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Confirmar
                    contraseña</label>
                <input type="password" id="confirm" name="password_confirmation"
                    class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                    placeholder="••••••••" required>
                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-brand-50 text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Crear
                Cuenta</button>
        </form>
    </div>

    <div class="mt-5 text-center text-sm text-ink-light">
        ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="font-semibold">Inicia sesión</a>
    </div>
@endsection