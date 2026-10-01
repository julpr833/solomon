@php
    $habits = auth()->user()->habitos;
    //dd($habits[0]->getCurrentRacha());
    //dd($habits[0])
@endphp

<div>
    <h2 class="my-5 text-base font-bold text-brand-90">Tus hábitos</h2>

    <div class="rounded-[10px] bg-white p-4 shadow-card">
        @forelse ($habits as $habit)
            <x-habit-item :name="$habit['Objetivo']" :frequency="$habit['Frecuencia']" :priority="$habit['Prioridad']"
                :streak="$habit->getCurrentRacha()" :done="$habit['done']" :href="$habit['href']" />
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