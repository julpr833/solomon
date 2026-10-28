@extends('layouts.app')

@section('title', 'Solomon — Metas y Recompensas')

@section('topbar')
    <x-topbar title="Metas y Recompensas">
        <x-slot:right>
            @include('partials.avatar', ['user' => auth()->user()])
        </x-slot:right>
    </x-topbar>
@endsection

@section('content')
    @if (session('status'))
        <div class="mb-4 rounded-lg border border-[#2E7D32]/30 bg-[#2E7D32]/10 px-4 py-3 text-sm font-semibold text-[#2E7D32]">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-danger/30 bg-danger/10 px-4 py-3 text-sm font-semibold text-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <h2 class="my-5 text-base font-bold text-brand-90">Metas activas</h2>

    <div class="rounded-[10px] bg-white p-4 shadow-card">
        @forelse ($metas as $meta)
            @php
                $racha = (int) $meta->habito->getCurrentRacha();
                $req = (int) $meta->RachaRequerida;
                $progreso = $req > 0 ? min(100, (int) round($racha / $req * 100)) : 0;
                $cumplida = $racha >= $req;
                $recompensa = $meta->recompensas->first();
            @endphp
            <div class="flex items-center gap-3 border-b border-line py-3.5 last:border-b-0">
                <div class="flex-1">
                    <div class="text-[13px] font-semibold text-ink">
                        <small class="font-normal !text-brand-70">{{ $meta->habito->Objetivo }}</small> · {{ $req }} días seguidos
                        <span class="text-ink-light">· {{ $racha }}/{{ $req }}@if ($cumplida) <i class="ti ti-check !text-[#2E7D32]"></i>@endif</span>
                    </div>
                    <div class="mt-1.5 h-1.5 overflow-hidden rounded-[3px] bg-line">
                        <div class="h-full rounded-[3px] {{ $cumplida ? '!bg-[#2E7D32]' : 'bg-brand-50' }} transition-[width] duration-300" style="width: {{ $progreso }}%;"></div>
                    </div>
                </div>
                <div class="flex w-20 shrink-0 flex-col items-center gap-0.5">
                    @if ($recompensa)
                        <div class="text-lg leading-none">{{ $recompensa->Emoji }}</div>
                        <div class="text-center text-[11px] leading-tight text-ink-light">{{ $recompensa->Nombre }}</div>
                    @else
                        <div class="cursor-pointer text-xs !text-brand-70" onclick="openGoalEdit({{ $meta->Habito_ID }}, {{ $meta->RachaRequerida }}, {{ $meta->Repetible ? 1 : 0 }}, null, @js($meta->habito->Objetivo))"><i class="ti ti-plus"></i> Añadir</div>
                    @endif
                </div>
                <div class="flex shrink-0 flex-col gap-1 text-ink-light">
                    <button type="button" class="cursor-pointer hover:text-brand-70" title="Editar meta"
                        onclick="openGoalEdit({{ $meta->Habito_ID }}, {{ $meta->RachaRequerida }}, {{ $meta->Repetible ? 1 : 0 }}, {{ $recompensa?->ID_Recompensa ?? 'null' }}, @js($meta->habito->Objetivo))"><i class="ti ti-pencil"></i></button>
                    <button type="button" class="cursor-pointer hover:text-danger" title="Eliminar meta"
                        onclick="openGoalDelete({{ $meta->Habito_ID }}, {{ $meta->RachaRequerida }}, @js($meta->habito->Objetivo))"><i class="ti ti-trash"></i></button>
                </div>
            </div>
        @empty
            <p class="py-3 text-center text-sm text-ink-light">
                No tienes metas creadas.
                <button type="button" class="cursor-pointer font-semibold text-brand-70 underline" onclick="document.getElementById('goalDialog').classList.remove('hidden')">Crea tu primera meta</button>
            </p>
        @endforelse
    </div>

    <h2 class="my-5 text-base font-bold text-brand-90">Tus recompensas</h2>

    <div class="rounded-[10px] bg-white p-4 shadow-card">
        @forelse ($recompensas as $recompensa)
            <div class="mb-2 flex items-center gap-3 rounded-lg bg-page p-3 last:mb-0">
                <div class="text-xl leading-none">{{ $recompensa->Emoji }}</div>
                <div class="flex-1">
                    <h4 class="m-0 text-[15px] font-semibold text-ink">{{ $recompensa->Nombre }}</h4>
                    @if ($recompensa->Descripcion)
                        <p class="m-0 text-[12px] text-ink-light">{{ $recompensa->Descripcion }}</p>
                    @endif
                </div>
                <button type="button" class="cursor-pointer text-ink-light hover:text-brand-70" title="Editar recompensa"
                    onclick="openRewardEdit({{ $recompensa->ID_Recompensa }}, @js($recompensa->Nombre), @js($recompensa->Descripcion ?? ''), @js($recompensa->Emoji))"><i class="ti ti-pencil"></i></button>
                <button type="button" class="cursor-pointer text-ink-light hover:text-danger" title="Eliminar recompensa"
                    onclick="openRewardDelete({{ $recompensa->ID_Recompensa }}, @js($recompensa->Nombre))"><i class="ti ti-trash"></i></button>
            </div>
        @empty
            <p class="py-3 text-center text-sm text-ink-light">
                No tienes recompensas creadas.
                <button type="button" class="cursor-pointer font-semibold text-brand-70 underline" onclick="document.getElementById('rewardDialog').classList.remove('hidden')">Crea tu primera recompensa</button>
            </p>
        @endforelse
    </div>

    <div class="mt-4 flex gap-3">
        <button type="button" class="inline-flex flex-1 items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-transparent text-brand-70 border-[1.5px] border-brand-70 hover:bg-brand-70 hover:text-white transition-opacity duration-200 active:scale-[0.98]" onclick="document.getElementById('goalDialog').classList.remove('hidden')"><i class="ti ti-plus"></i> Nueva meta</button>
        <button type="button" class="inline-flex flex-1 items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-transparent text-brand-70 border-[1.5px] border-brand-70 hover:bg-brand-70 hover:text-white transition-opacity duration-200 active:scale-[0.98]" onclick="document.getElementById('rewardDialog').classList.remove('hidden')"><i class="ti ti-plus"></i> Nueva recompensa</button>
    </div>
@endsection

@section('footer')
    {{-- Crear recompensa --}}
    <div id="rewardDialog" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-brand-95/50 modal-backdrop" onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="max-h-[90vh] w-[90%] max-w-[440px] overflow-y-auto rounded-[14px] bg-white p-6 shadow-[0_8px_32px_rgba(5,25,35,0.2)] modal-card">
            <span class="float-right cursor-pointer text-[22px] leading-none text-ink-light hover:text-ink" onclick="document.getElementById('rewardDialog').classList.add('hidden')">&times;</span>
            <h2 class="mb-5 text-lg font-bold text-brand-90">Nueva Recompensa</h2>
            <form method="POST" action="{{ route('reward.create') }}">
                @csrf
                <div class="mb-4">
                    <label for="rname" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Nombre</label>
                    <input type="text" id="rname" name="nombre" maxlength="45" required class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50" placeholder="Ej: Helado de chocolate">
                </div>
                <div class="mb-4">
                    <label for="rdesc" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Descripción (opcional)</label>
                    <textarea id="rdesc" name="descripcion" maxlength="150" class="block w-full resize-y min-h-20 px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50" placeholder="¿Qué te vas a regalar?"></textarea>
                </div>
                <div class="mb-4">
                    <span class="mb-1.5 block text-[13px] font-semibold text-brand-95">Emoji</span>
                    <x-emoji-picker id="remoji" />
                </div>
                <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-brand-50 text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Crear Recompensa</button>
            </form>
        </div>
    </div>

    {{-- Crear meta --}}
    <div id="goalDialog" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-brand-95/50 modal-backdrop" onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="max-h-[90vh] w-[90%] max-w-[440px] overflow-y-auto rounded-[14px] bg-white p-6 shadow-[0_8px_32px_rgba(5,25,35,0.2)] modal-card">
            <span class="float-right cursor-pointer text-[22px] leading-none text-ink-light hover:text-ink" onclick="document.getElementById('goalDialog').classList.add('hidden')">&times;</span>
            <h2 class="mb-5 text-lg font-bold text-brand-90">Nueva Meta</h2>
            <form method="POST" action="{{ route('goals.create') }}">
                @csrf
                <div class="mb-4">
                    <label for="ghabit" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Vincular a hábito</label>
                    <select id="ghabit" name="habito_id" required class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                        @forelse ($habitos as $habito)
                            <option value="{{ $habito->ID_Habito }}">{{ $habito->Objetivo }}</option>
                        @empty
                            <option value="">Sin hábitos disponibles</option>
                        @endforelse
                    </select>
                </div>
                <div class="mb-4">
                    <label for="gdias" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Días requeridos</label>
                    <input type="number" id="gdias" name="racha_requerida" class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50" value="{{ old('racha_requerida', 30) }}" min="1" max="365" required>
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
                        @foreach ($recompensas as $recompensa)
                            <option value="{{ $recompensa->ID_Recompensa }}">{{ $recompensa->Emoji }} {{ $recompensa->Nombre }}</option>
                        @endforeach
                        <option value="">Sin recompensa</option>
                    </select>
                </div>
                <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-brand-50 text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Crear Meta</button>
            </form>
        </div>
    </div>

    {{-- Editar meta --}}
    <div id="goalEditDialog" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-brand-95/50 modal-backdrop" onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="max-h-[90vh] w-[90%] max-w-[440px] overflow-y-auto rounded-[14px] bg-white p-6 shadow-[0_8px_32px_rgba(5,25,35,0.2)] modal-card">
            <span class="float-right cursor-pointer text-[22px] leading-none text-ink-light hover:text-ink" onclick="document.getElementById('goalEditDialog').classList.add('hidden')">&times;</span>
            <h2 class="mb-5 text-lg font-bold text-brand-90">Editar Meta</h2>
            <p class="mb-4 text-sm text-ink-light" id="goalEditIdentity"></p>
            <form id="goalEditForm" method="POST" action="{{ route('goals.edit') }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="habito_id">
                <input type="hidden" name="racha_requerida">
                <div class="mb-4">
                    <label for="ge-repeat" class="mb-1.5 block text-[13px] font-semibold text-brand-95">¿Repetible?</label>
                    <select id="ge-repeat" name="repetible" class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                        <option value="0">No, una sola vez</option>
                        <option value="1">Sí, se puede repetir</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="ge-reward" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Recompensa</label>
                    <select id="ge-reward" name="recompensa_id" class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                        @foreach ($recompensas as $recompensa)
                            <option value="{{ $recompensa->ID_Recompensa }}">{{ $recompensa->Emoji }} {{ $recompensa->Nombre }}</option>
                        @endforeach
                        <option value="">Sin recompensa</option>
                    </select>
                </div>
                <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-brand-50 text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Guardar cambios</button>
            </form>
        </div>
    </div>

    {{-- Eliminar meta --}}
    <div id="goalDeleteDialog" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-brand-95/50 modal-backdrop" onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="max-h-[90vh] w-[90%] max-w-[440px] overflow-y-auto rounded-[14px] bg-white p-6 shadow-[0_8px_32px_rgba(5,25,35,0.2)] modal-card">
            <span class="float-right cursor-pointer text-[22px] leading-none text-ink-light hover:text-ink" onclick="document.getElementById('goalDeleteDialog').classList.add('hidden')">&times;</span>
            <h2 class="mb-5 text-lg font-bold text-brand-90">Eliminar Meta</h2>
            <p class="mb-5 text-sm text-ink-light">¿Estás seguro de que querés eliminar la meta <strong class="text-ink" id="goalDeleteName">""</strong>?</p>
            <div class="flex gap-3">
                <button type="button" class="inline-flex flex-1 items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-transparent text-brand-70 border-[1.5px] border-brand-70 hover:bg-brand-70 hover:text-white transition-opacity duration-200 active:scale-[0.98]" onclick="document.getElementById('goalDeleteDialog').classList.add('hidden')">Cancelar</button>
                <form id="goalDeleteForm" method="POST" action="{{ route('goals.delete') }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="habito_id">
                    <input type="hidden" name="racha_requerida">
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-danger text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Eliminar</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Editar recompensa --}}
    <div id="rewardEditDialog" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-brand-95/50 modal-backdrop" onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="max-h-[90vh] w-[90%] max-w-[440px] overflow-y-auto rounded-[14px] bg-white p-6 shadow-[0_8px_32px_rgba(5,25,35,0.2)] modal-card">
            <span class="float-right cursor-pointer text-[22px] leading-none text-ink-light hover:text-ink" onclick="document.getElementById('rewardEditDialog').classList.add('hidden')">&times;</span>
            <h2 class="mb-5 text-lg font-bold text-brand-90">Editar Recompensa</h2>
            <form id="rewardEditForm" method="POST" action="{{ route('reward.edit') }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="recompensa_id">
                <div class="mb-4">
                    <label for="re-name" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Nombre</label>
                    <input type="text" id="re-name" name="nombre" maxlength="45" required class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50" placeholder="Ej: Helado de chocolate">
                </div>
                <div class="mb-4">
                    <label for="re-desc" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Descripción (opcional)</label>
                    <textarea id="re-desc" name="descripcion" maxlength="150" class="block w-full resize-y min-h-20 px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50" placeholder="¿Qué te vas a regalar?"></textarea>
                </div>
                <div class="mb-4">
                    <span class="mb-1.5 block text-[13px] font-semibold text-brand-95">Emoji</span>
                    <x-emoji-picker id="re-emoji" />
                </div>
                <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-brand-50 text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Guardar cambios</button>
            </form>
        </div>
    </div>

    {{-- Eliminar recompensa --}}
    <div id="rewardDeleteDialog" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-brand-95/50 modal-backdrop" onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="max-h-[90vh] w-[90%] max-w-[440px] overflow-y-auto rounded-[14px] bg-white p-6 shadow-[0_8px_32px_rgba(5,25,35,0.2)] modal-card">
            <span class="float-right cursor-pointer text-[22px] leading-none text-ink-light hover:text-ink" onclick="document.getElementById('rewardDeleteDialog').classList.add('hidden')">&times;</span>
            <h2 class="mb-5 text-lg font-bold text-brand-90">Eliminar Recompensa</h2>
            <p class="mb-5 text-sm text-ink-light">¿Estás seguro de que querés eliminar la recompensa <strong class="text-ink" id="rewardDeleteName">""</strong>? Se desvinculará de tus metas.</p>
            <div class="flex gap-3">
                <button type="button" class="inline-flex flex-1 items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-transparent text-brand-70 border-[1.5px] border-brand-70 hover:bg-brand-70 hover:text-white transition-opacity duration-200 active:scale-[0.98]" onclick="document.getElementById('rewardDeleteDialog').classList.add('hidden')">Cancelar</button>
                <form id="rewardDeleteForm" method="POST" action="{{ route('reward.delete') }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="recompensa_id">
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-danger text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Eliminar</button>
                </form>
            </div>
        </div>
    </div>

    <button type="button" class="fixed right-6 bottom-6 z-20 flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 text-[28px] text-white shadow-[0_4px_16px_rgba(0,166,251,0.35)] cursor-pointer transition-transform duration-200 active:scale-90" onclick="document.getElementById('goalDialog').classList.remove('hidden')"><i class="ti ti-plus"></i></button>

    @include('partials.bottom-nav', ['active' => 'goals'])
@endsection

@section('scripts')
    <script>
        function setEmojiFieldValue(inputId, emoji) {
            const input = document.getElementById(inputId);
            if (!input) return;

            const value = emoji?.trim() || '🏆';
            const preview = input.closest('[data-emoji-field]')?.querySelector('[data-emoji-preview]');

            input.value = value;
            if (preview) preview.textContent = value;
        }

        window.setEmojiFieldValue = setEmojiFieldValue;

        function closeEmojiPickers() {
            document.querySelectorAll('[data-emoji-panel]').forEach(panel => panel.classList.add('hidden'));
            document.querySelectorAll('[data-emoji-trigger]').forEach(trigger => trigger.setAttribute('aria-expanded', 'false'));
        }

        function toggleEmojiPicker(trigger) {
            const panel = trigger.closest('[data-emoji-field]')?.querySelector('[data-emoji-panel]');
            if (!panel) return;

            const open = panel.classList.contains('hidden');
            closeEmojiPickers();

            if (open) {
                panel.classList.remove('hidden');
                trigger.setAttribute('aria-expanded', 'true');
            }
        }

        document.addEventListener('click', (event) => {
            if (event.target.closest('[data-emoji-field]')) return;
            closeEmojiPickers();
        });

        function openGoalEdit(habitoId, racha, repetible, recompensaId, habitName) {
            const form = document.getElementById('goalEditForm');
            form.querySelector('[name="habito_id"]').value = habitoId;
            form.querySelector('[name="racha_requerida"]').value = racha;
            form.querySelector('[name="repetible"]').value = repetible ? '1' : '0';
            form.querySelector('[name="recompensa_id"]').value = recompensaId ?? '';
            document.getElementById('goalEditIdentity').textContent = habitName + ' · ' + racha + ' días seguidos';
            document.getElementById('goalEditDialog').classList.remove('hidden');
        }

        function openGoalDelete(habitoId, racha, habitName) {
            const form = document.getElementById('goalDeleteForm');
            form.querySelector('[name="habito_id"]').value = habitoId;
            form.querySelector('[name="racha_requerida"]').value = racha;
            document.getElementById('goalDeleteName').textContent = '"' + habitName + ' · ' + racha + ' días"';
            document.getElementById('goalDeleteDialog').classList.remove('hidden');
        }

        function openRewardEdit(id, nombre, descripcion, emoji) {
            const form = document.getElementById('rewardEditForm');
            form.querySelector('[name="recompensa_id"]').value = id;
            document.getElementById('re-name').value = nombre;
            document.getElementById('re-desc').value = descripcion ?? '';
            setEmojiFieldValue('re-emoji', emoji);
            document.getElementById('rewardEditDialog').classList.remove('hidden');
        }

        function openRewardDelete(id, nombre) {
            const form = document.getElementById('rewardDeleteForm');
            form.querySelector('[name="recompensa_id"]').value = id;
            document.getElementById('rewardDeleteName').textContent = '"' + nombre + '"';
            document.getElementById('rewardDeleteDialog').classList.remove('hidden');
        }
    </script>

    <script type="module">
        import 'https://cdn.jsdelivr.net/npm/emoji-picker-element@1.29.1/index.js';
        import es from 'https://cdn.jsdelivr.net/npm/emoji-picker-element@1.29.1/i18n/es.js';

        document.querySelectorAll('emoji-picker').forEach((picker) => {
            picker.i18n = es;
            picker.addEventListener('emoji-click', (event) => {
                const field = picker.closest('[data-emoji-field]');
                const input = field?.querySelector('[data-emoji-value]');

                if (input) window.setEmojiFieldValue(input.id, event.detail.unicode);

                field?.querySelector('[data-emoji-panel]')?.classList.add('hidden');
                field?.querySelector('[data-emoji-trigger]')?.setAttribute('aria-expanded', 'false');
            });
        });
    </script>
@endsection
