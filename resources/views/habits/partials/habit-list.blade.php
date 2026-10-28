<div>
    <h2 class="my-5 text-base font-bold text-brand-90">Tus hábitos</h2>

    <div class="rounded-[10px] bg-white p-4 shadow-card">
        @forelse ($habits as $habit)
            <x-habit-item :id="$habit->ID_Habito" :name="$habit->Objetivo" :description="$habit->Descripcion"
                :frequency="$habit->Frecuencia" :priority="$habit->Prioridad"
                :reminder="$habit->recordatorio?->ProximaFecha?->format('H:i')" :streak="$habit->getCurrentRacha()"
                :done="$habit->isCompletedToday()" :href="route('habit', $habit->ID_Habito)" />
        @empty
            <p class="text-slate-700 text-center text-sm">
                No tienes ningún hábito creado.
                <button type="button" class="cursor-pointer text-sm font-semibold text-brand-70 underline"
                    onclick="document.getElementById('habitDialog').classList.remove('hidden')">Haz
                    click aquí para crear</button>
            </p>
        @endforelse
    </div>
</div>