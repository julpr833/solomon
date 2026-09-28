@extends('layouts.auth')

@section('title', 'Solomon — Restablecer Contraseña')

@section('content')
    <div class="mb-8 text-center">
        <img src="{{ asset('logo-base.png') }}" alt="Solomon" class="mx-auto mb-3 w-16 h-16 object-contain">
        <h1 class="text-[32px] font-extrabold text-brand-90 tracking-[-0.5px] leading-tight">Solomon</h1>
        <span class="mt-1 block text-xs text-ink-light">Restablecer contraseña</span>
    </div>

    <div class="rounded-[10px] bg-white p-4 shadow-card">
        @if (session('status'))
            <p class="mb-4 rounded-lg bg-green-bg px-3 py-2 text-sm font-medium text-green">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('password.update') }}" id="resetForm" data-success="Tu contraseña fue actualizada. Ya podés iniciar sesión.">
            @csrf
            <input type="hidden" name="token" value="{{ $resetToken }}">
            <input type="hidden" name="email" value="{{ old('email', $resetEmail ?? '') }}">
            <div id="reset-error" class="mb-4 hidden rounded-lg bg-orange-bg px-3 py-2 text-sm text-orange"></div>
            <div id="reset-success" class="mb-4 hidden rounded-lg bg-green-bg px-3 py-2 text-sm font-medium text-green"></div>

            <div class="mb-4">
                <label for="password" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Nueva contraseña</label>
                <input type="password" id="password" name="password"
                    class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                    placeholder="••••••••" required>
                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="confirm" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Confirmar contraseña</label>
                <input type="password" id="confirm" name="password_confirmation"
                    class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                    placeholder="••••••••" required>
                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" id="reset-submit"
                class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-brand-50 text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Restablecer
                contraseña <i id="reset-loader" class="ti ti-loader hidden animate-spin"></i></button>
        </form>
    </div>

    <div class="mt-5 text-center text-sm text-ink-light">
        ¿Recordás tu contraseña? <a href="{{ route('login') }}" class="font-semibold">Inicia sesión</a>
    </div>

    <script>
        function showFormMessage(el, message, isError) {
            el.textContent = message;
            el.classList.remove('hidden');
            if (isError) {
                el.classList.remove('bg-green-bg', 'text-green', 'font-medium');
                el.classList.add('bg-orange-bg', 'text-orange');
            } else {
                el.classList.remove('bg-orange-bg', 'text-orange');
                el.classList.add('bg-green-bg', 'text-green', 'font-medium');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('resetForm');
            const errBox = document.getElementById('reset-error');
            const okBox = document.getElementById('reset-success');
            const submitBtn = document.getElementById('reset-submit');
            const loader = document.getElementById('reset-loader');
            const genericError = 'Hubo un error. Intentalo de nuevo.';

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                errBox.classList.add('hidden');
                okBox.classList.add('hidden');

                submitBtn.disabled = true;
                if (loader) loader.classList.remove('hidden');

                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(form),
                    });

                    const contentType = res.headers.get('content-type') || '';
                    let data = null;
                    if (contentType.includes('application/json')) {
                        try { data = await res.json(); } catch (e) { data = null; }
                    }

                    if (res.status === 422) {
                        const first = data && data.errors ? Object.values(data.errors).flat()[0] : null;
                        showFormMessage(errBox, first || genericError, true);
                        return;
                    }

                    if (res.ok) {
                        if (data && data.errors && Object.keys(data.errors).length) {
                            const first = Object.values(data.errors).flat()[0];
                            showFormMessage(errBox, first || genericError, true);
                        } else {
                            showFormMessage(okBox, (data && data.status) || form.dataset.success, false);
                            form.reset();
                        }
                    } else {
                        showFormMessage(errBox, genericError, true);
                    }
                } catch (err) {
                    showFormMessage(errBox, genericError, true);
                } finally {
                    submitBtn.disabled = false;
                    if (loader) loader.classList.add('hidden');
                }
            });
        });
    </script>
@endsection