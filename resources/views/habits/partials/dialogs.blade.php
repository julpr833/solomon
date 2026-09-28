<button type="button"
    class="fixed right-6 bottom-6 z-20 flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 text-[28px] text-white shadow-[0_4px_16px_rgba(0,166,251,0.35)] cursor-pointer transition-transform duration-200 active:scale-90"
    onclick="document.getElementById('habitDialog').classList.remove('hidden')"><i class="ti ti-plus"></i></button>

<div id="habitDialog"
    class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-brand-95/50 modal-backdrop"
    onclick="if(event.target===this)this.classList.add('hidden')">
    <div
        class="max-h-[90vh] w-[90%] max-w-[440px] overflow-y-auto rounded-[14px] bg-white p-6 shadow-[0_8px_32px_rgba(5,25,35,0.2)] modal-card">
        <span class="float-right cursor-pointer text-[22px] leading-none text-ink-light hover:text-ink"
            onclick="document.getElementById('habitDialog').classList.add('hidden')">&times;</span>
        <h2 class="mb-5 text-lg font-bold text-brand-90">Nuevo Hábito</h2>
        <form method="POST" action="{{ route('habit.create') }}">
            @csrf
            <div class="mb-4">
                <label for="hname" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Nombre del
                    hábito</label>
                <input type="text" id="hname" name="objetivo"
                    class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                    placeholder="Ej: Leer 15 minutos">
            </div>
            <div class="mb-4">
                <label for="hdesc" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Descripción
                    (opcional)</label>
                <textarea id="hdesc" name="descripcion"
                    class="block w-full resize-y min-h-20 px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50"
                    placeholder="¿Por qué quieres crear este hábito?"></textarea>
            </div>
            <div class="mb-4">
                <label for="hfreq" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Frecuencia</label>
                <select id="hfreq" name="frecuencia"
                    class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                    <option value="Pluridiaria">Varias veces al día</option>
                    <option value="Daria" selected>Diario</option>
                    <option value="Semanal">Semanal</option>
                    <option value="Mensual">Mensual</option>
                    <option value="Mensual">Anual</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="hpriority" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Prioridad</label>
                <select id="hpriority" name="prioridad"
                    class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                    <option value="Alta">Alta</option>
                    <option value="Media" selected>Media</option>
                    <option value="Baja">Baja</option>
                </select>
            </div>
            <button type="submit"
                class="inline-flex w-full items-center justify-center gap-1.5 px-6 py-3 rounded-lg font-semibold text-[15px] cursor-pointer bg-brand-50 text-white hover:opacity-90 transition-opacity duration-200 active:scale-[0.98]">Crear
                Hábito</button>
        </form>
    </div>
</div>

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
                    placeholder="Ej: Leer 15 minutos">
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
                    <option value="Daria">Diario</option>
                    <option value="Semanal">Semanal</option>
                    <option value="Mensual">Mensual</option>
                    <option value="Mensual">Anual</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="e-priority" class="mb-1.5 block text-[13px] font-semibold text-brand-95">Prioridad</label>
                <select id="e-priority" name="prioridad"
                    class="block w-full px-3.5 py-3 border-[1.5px] border-line rounded-lg bg-white text-ink outline-none transition-colors duration-200 focus:border-brand-50">
                    <option value="Alta">Alta</option>
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
        <p class="mb-5 text-sm text-ink-light">¿Estás seguro de que querés eliminar el hábito <strong class="text-ink"
                id="delete-name">""</strong>? Se perderá todo su historial y progreso.</p>
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