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
            <div class="mt-3 text-center text-sm">
                <a href="#" class="font-medium text-brand-70 hover:underline" onclick="event.preventDefault(); openForgotModal();">¿Olvidaste tu contraseña?</a>
            </div>
        </form>
    </div>

    <div class="mt-5 text-center text-sm text-ink-light">
        ¿No tienes cuenta? <a href="{{ route('signup') }}" class="font-semibold">Regístrate</a>
    </div>

    <div id="forgotModal"
        class="fixed inset-0 z-100 hidden items-center justify-center bg-brand-95/50"
        @if (session('status') || isset($resetToken)) style="display:flex;" @endif
        onclick="if(event.target===this) closeForgotModal()">
        <div class="max-h-[90vh] w-[90%] max-w-110 overflow-y-auto rounded-[14px] bg-white p-6 shadow-[0_8px_32px_rgba(5,25,35,0.2)]">
            <span class="float-right cursor-pointer text-[22px] leading-none text-ink-light hover:text-ink" onclick="closeForgotModal()">&times;</span>

            <div id="forgot-panel" @isset($resetToken) class="hidden" @endisset>
                <h2 class="mb-5 text-lg font-bold text-brand-90">Recuperar contraseña</h2>
                <p class="mb-4 text-sm text-ink-light">Ingresá el correo con el que te registraste y te enviamos un enlace para restablecer tu contraseña.</p>

                <form method="POST" action="{{ route('forgotpassword') }}" id="forgotForm" data-success="Si el correo electrónico existe, te enviamos un enlace para restablecer tu contraseña.">
                    @csrf
                    @if (session('status'))
                        <p class="mb-4 rounded-lg bg-green-bg px-3 py-2 text-sm font-medium text-green">{{ session('status') }}</p>
                    @endif
                    <div id="forgot-error" class="mb-4 hidden rounded-lg bg-orange-bg px-3 py-2 text-sm text-orange"></div>
                    <div id="forgot-success" class="mb-4 hidden rounded-lg bg-green-bg px-3 py-2 text-sm font-medium text-green"></div>
                    <div class="mb-4">
                        <label for="forgot-email" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Correo electrónico</label>
                        <input type="email" id="forgot-email" name="email" value="{{ old('email') }}"
                            class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                            placeholder="tu@correo.com" required>
                    </div>
                    <button type="submit" id="forgot-submit"
                        class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-brand-50 text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Enviar
                        enlace <i id="forgot-loader" class="ti ti-loader hidden animate-spin"></i></button>
                </form>
            </div>

            <div id="reset-panel" class="@empty($resetToken) hidden @endempty">
                <h2 class="mb-5 text-lg font-bold text-brand-90">Restablecer contraseña</h2>
                <p class="mb-4 text-sm text-ink-light">Elegí una nueva contraseña para tu cuenta.</p>

                <form method="POST" action="{{ route('password.update') }}" id="resetForm" data-success="Tu contraseña fue actualizada. Ya podés iniciar sesión.">
                    @csrf
                    <div id="reset-error" class="mb-4 hidden rounded-lg bg-orange-bg px-3 py-2 text-sm text-orange"></div>
                    <div id="reset-success" class="mb-4 hidden rounded-lg bg-green-bg px-3 py-2 text-sm font-medium text-green"></div>
                    <input type="hidden" name="token" value="{{ $resetToken ?? '' }}">
                    <div class="mb-4">
                        <label for="reset-email" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Correo electrónico</label>
                        <input type="email" id="reset-email" name="email" value="{{ old('email', $resetEmail ?? '') }}"
                            class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                            placeholder="tu@correo.com" required>
                    </div>
                    <div class="mb-4">
                        <label for="reset-password" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Nueva contraseña</label>
                        <input type="password" id="reset-password" name="password"
                            class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                            placeholder="••••••••" required>
                    </div>
                    <div class="mb-4">
                        <label for="reset-confirm" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Confirmar contraseña</label>
                        <input type="password" id="reset-confirm" name="password_confirmation"
                            class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                            placeholder="••••••••" required>
                    </div>
                    <button type="submit" id="reset-submit"
                        class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-brand-50 text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Restablecer
                        contraseña <i id="reset-loader" class="ti ti-loader hidden animate-spin"></i></button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openForgotModal() {
            document.getElementById('forgot-panel').classList.remove('hidden');
            document.getElementById('reset-panel').classList.add('hidden');
            const modal = document.getElementById('forgotModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
        }

        function closeForgotModal() {
            const modal = document.getElementById('forgotModal');
            modal.classList.add('hidden');
            modal.style.display = '';
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeForgotModal();
        });

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

        function bindModalForm(formId, errId, okId, submitId, loaderId) {
            const form = document.getElementById(formId);
            if (!form) return;

            const errBox = document.getElementById(errId);
            const okBox = document.getElementById(okId);
            const submitBtn = document.getElementById(submitId);
            const loader = document.getElementById(loaderId);

            const genericError = 'Hubo un error. Intentalo de nuevo.';
            const fallbackSuccess = form.dataset.success || 'Listo.';

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
                            showFormMessage(okBox, (data && data.status) || fallbackSuccess, false);
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
        }

        document.addEventListener('DOMContentLoaded', () => {
            bindModalForm('forgotForm', 'forgot-error', 'forgot-success', 'forgot-submit', 'forgot-loader');
            bindModalForm('resetForm', 'reset-error', 'reset-success', 'reset-submit', 'reset-loader');
        });
    </script>
@endsection