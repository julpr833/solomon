<script>
    window.addEventListener('pageshow', (event) => {
        if (event.persisted) window.location.reload();
    });

    async function toggleHabit(button) {
        const url = button.dataset.completeUrl;

        if (!url || button.disabled) return;

        const completed = button.getAttribute('aria-pressed') === 'true';
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        button.disabled = true;

        try {
            const response = await fetch(url, {
                method: completed ? 'DELETE' : 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('No se pudo actualizar el hábito.');

            const data = await response.json();
            const habit = button.closest('[data-habit-id]');
            const streak = habit?.querySelector('[data-habit-streak]');
            const completedToday = document.querySelector('[data-today-completed]');

            if (completed) {
                button.classList.remove('border-brand-50', 'bg-brand-50');
                button.classList.add('border-brand-70');
                button.innerHTML = '';
                button.setAttribute('aria-pressed', 'false');
                button.setAttribute('aria-label', 'Marcar hábito como completado');
            } else {
                button.classList.remove('border-brand-70');
                button.classList.add('border-brand-50', 'bg-brand-50');
                button.innerHTML = '<i class="ti ti-check"></i>';
                button.setAttribute('aria-pressed', 'true');
                button.setAttribute('aria-label', 'Hábito completado hoy');
            }

            if (streak) {
                streak.innerHTML = `${Number(data.streak)} <i class="ti ti-bolt"></i>`;
            }

            if (completedToday && data.changed) {
                const total = Number(completedToday.textContent) + (completed ? -1 : 1);
                completedToday.textContent = Math.max(0, total);
            }
        } catch (error) {
            console.error(error);
        } finally {
            button.disabled = false;
        }
    }

    function openEdit(element) {
        const habit = element.closest('[data-habit-id]');
        document.getElementById('e-id').value = habit.dataset.habitId;
        document.getElementById('e-name').value = habit.dataset.habitName;
        document.getElementById('e-desc').value = habit.dataset.habitDescription || '';
        document.getElementById('e-freq').value = habit.dataset.habitFrequency;
        document.getElementById('e-priority').value = habit.dataset.habitPriority;
        document.getElementById('e-reminder').value = habit.dataset.habitReminder || '';
        document.getElementById('editDialog').classList.remove('hidden');
    }

    function openDelete(element) {
        const habit = element.closest('[data-habit-id]');
        document.getElementById('delete-id').value = habit.dataset.habitId;
        document.getElementById('delete-name').textContent = '"' + habit.dataset.habitName + '"';
        document.getElementById('deleteDialog').classList.remove('hidden');
    }

    var refreshBtnTimeout = -1;

    const motivationDisabledClasses = [
        'bg-white', 'border-2', 'border-dashed', 'border-line', 'shadow-none', 'text-ink-light'
    ];

    function setMotivationDisabled(card, tag, quote, source, disabled) {
        motivationDisabledClasses.forEach(c => card.classList.toggle(c, disabled));
        if (tag) {
            tag.classList.toggle('bg-page', disabled);
            tag.classList.toggle('text-ink-light', disabled);
        }
        if (quote) {
            quote.classList.toggle('italic', disabled);
            quote.classList.toggle('text-sm', disabled);
        }
        if (source && disabled) source.textContent = '';
    }

    function initMotivation() {
        const card = document.getElementById('motivation-card');
        const tag = document.getElementById('motivation-tag');
        const typeEl = document.getElementById('motivation-type');
        const quoteEl = document.getElementById('motivation-quote');
        const sourceEl = document.getElementById('motivation-source');
        const refreshBtn = document.getElementById('motivation-refresh');
        const refreshIcon = document.getElementById('motivation-refresh-icon');


        if (!card) return;

        const motivationEnabled = {{ $motivationEnabled ? 'true' : 'false' }};
        const pool = @js($motivation);

        if (!motivationEnabled) {
            setMotivationDisabled(card, tag, quoteEl, sourceEl, true);
            card.style.display = 'block';
            if (tag) tag.style.display = 'none';
            if (refreshBtn) refreshBtn.style.display = 'none';
            quoteEl.innerHTML = '<i class="ti ti-info-circle" style="vertical-align: middle; margin-right: 4px;"></i> Contenido motivacional desactivado. Habilítalo en <a href="{{ route('settings') }}" style="font-weight: 600; text-decoration: underline; color: var(--color-brand-70);">Ajustes</a> para inspirar tu día.';
            return;
        }

        setMotivationDisabled(card, tag, quoteEl, sourceEl, false);
        if (tag) tag.style.display = 'inline-flex';
        if (refreshBtn) refreshBtn.style.display = 'flex';
        card.style.display = 'block';

        if (pool.length === 0) {
            if (tag) tag.style.display = 'none';
            if (refreshBtn) refreshBtn.style.display = 'none';
            quoteEl.textContent = 'No hay contenido motivacional disponible.';
            sourceEl.textContent = '';
            return;
        }

        const currentQuote = quoteEl.textContent.replace(/"/g, '');
        let selected;
        let attempts = 0;
        do {
            selected = pool[Math.floor(Math.random() * pool.length)];
            attempts++;
        } while (selected.text === currentQuote && pool.length > 1 && attempts < 10);

        card.classList.remove('animate-fade-in');
        void card.offsetWidth;
        card.classList.add('animate-fade-in');

        if (typeEl) {
            typeEl.textContent = selected.type;
            const iconEl = tag.querySelector('i');
            if (iconEl) iconEl.className = 'ti ' + selected.icon;
        }
        quoteEl.textContent = `"${selected.text}"`;
        sourceEl.textContent = selected.ref;

        if (refreshBtn) {
            refreshBtn.onclick = () => {
                if (refreshIcon) {
                    if (refreshBtnTimeout != -1)
                        clearTimeout(refreshBtnTimeout);
                    refreshIcon.classList.remove('animate-spin');
                    quoteEl.classList.remove('animate-fade-in');
                    void refreshIcon.offsetWidth;
                    refreshIcon.classList.add('animate-spin');
                    quoteEl.classList.add('animate-fade-in');
                    refreshBtnTimeout = setTimeout(() => {
                        refreshIcon.classList.remove('animate-spin');
                        quoteEl.classList.remove('animate-fade-in');
                    }, 1000);
                    console.log(refreshBtnTimeout);
                }
                initMotivation();
            };
        }
    }

    function initWelcomeGreeting() {
        const prefixEl = document.getElementById('welcome-prefix');
        const usernameEl = document.getElementById('welcome-username');
        const savedName = localStorage.getItem('userName');
        const savedGender = localStorage.getItem('userGender');

        if (usernameEl && savedName && savedName.trim() !== '') usernameEl.textContent = savedName;
        if (prefixEl && savedGender === 'femenino') prefixEl.textContent = 'Bienvenida';
        else if (prefixEl) prefixEl.textContent = 'Bienvenido';
    }

    document.addEventListener('DOMContentLoaded', () => {
        initMotivation();
        initWelcomeGreeting();
    });
</script>