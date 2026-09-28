<p class="mb-4 text-sm text-ink-light">
    <span id="welcome-prefix">Bienvenido</span>, <strong class="text-ink"
        id="welcome-username">{{ auth()->user()?->NombreUsuario ?: 'Desconocido' }}</strong>
</p>

@if (empty(auth()->user()?->telefono))
    <div id="phone-warning-alert"
        class="mb-5 flex items-start gap-3 rounded-[10px] border-[1.5px] border-[#FFE0B2] bg-[#FFF9E6] px-4 py-3.5 shadow-card animate-fade-in"
        style="display: none;">
        <i class="ti ti-alert-circle mt-0.5 shrink-0 text-xl text-orange"></i>
        <div class="flex-1 text-[13.5px] leading-snug text-[#663C00]">
            No tienes un teléfono asignado, ingresa a los <a href="{{ route('settings') }}"
                class="font-semibold text-brand-70 underline">ajustes</a> para asignar uno y recibir recordatorios por
            Whatsapp!
        </div>
    </div>
@endif
