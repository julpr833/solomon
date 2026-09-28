<script>
    function openEdit(name, freq, priority) {
        document.getElementById('e-name').value = name;
        document.getElementById('e-freq').value = freq;
        document.getElementById('e-priority').value = priority;
        document.getElementById('editDialog').classList.remove('hidden');
    }

    function openDelete(name) {
        document.getElementById('delete-name').textContent = '"' + name + '"';
        document.getElementById('deleteDialog').classList.remove('hidden');
    }

    const proverbs = [
        { text: "La blanda respuesta quita la ira; mas la palabra áspera hace subir el furor.", ref: "Proverbios 15:1" },
        { text: "El que guarda su boca guarda su alma; mas el que mucho abre sus labios tendrá calamidad.", ref: "Proverbios 13:3" },
        { text: "Encomienda a Jehová tus obras, y tus pensamientos serán afirmados.", ref: "Proverbios 16:3" },
        { text: "El corazón alegre hermosea el rostro; mas por el dolor del corazón el espíritu se abate.", ref: "Proverbios 15:13" },
        { text: "Mejor es lo poco con el temor de Jehová, que el gran tesoro donde hay turbación.", ref: "Proverbios 15:16" },
        { text: "El que camina en integridad anda seguro; mas el que pervierte sus caminos será quebrantado.", ref: "Proverbios 10:9" },
        { text: "El corazón del hombre traza su rumbo, pero sus pasos los dirige el Señor.", ref: "Proverbios 16:9" },
        { text: "Como agua fría al alma sedienta, así son las buenas nuevas de lejanas tierras.", ref: "Proverbios 25:25" }
    ];

    const motivationalQuotes = [
        { text: "La única forma de hacer un gran trabajo es amar lo que haces.", ref: "Steve Jobs" },
        { text: "No juzgues cada día por la cosecha que recoges, sino por las semillas que plantas.", ref: "Robert Louis Stevenson" },
        { text: "La motivación es lo que te pone en marcha. El hábito es lo que hace que sigas.", ref: "Jim Ryun" },
        { text: "El éxito no es la clave de la felicidad. La felicidad es la clave del éxito.", ref: "Albert Schweitzer" },
        { text: "Los pequeños hábitos diarios son los que construyen los grandes resultados.", ref: "Anónimo" },
        { text: "Cree que puedes y casi habrás llegado.", ref: "Theodore Roosevelt" },
        { text: "No cuentes los días, haz que los días cuenten.", ref: "Muhammad Ali" },
        { text: "La disciplina es el puente entre las metas y los logros.", ref: "Jim Rohn" }
    ];

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

        const provSaved = localStorage.getItem('proverbiosBiblicos');
        const quoteSaved = localStorage.getItem('frasesMotivacionales');

        const showProverbs = provSaved === null ? true : provSaved === 'true';
        const showQuotes = quoteSaved === null ? true : quoteSaved === 'true';

        if (!showProverbs && !showQuotes) {
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

        let pool = [];
        if (showProverbs) pool = pool.concat(proverbs.map(p => ({ ...p, type: 'Proverbio Bíblico', icon: 'ti-book' })));
        if (showQuotes) pool = pool.concat(motivationalQuotes.map(q => ({ ...q, type: 'Frase Motivacional', icon: 'ti-quote' })));

        if (pool.length === 0) return;

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
                    refreshIcon.classList.remove('animate-spin');
                    void refreshIcon.offsetWidth;
                    refreshIcon.classList.add('animate-spin');
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

    function checkPhoneNumberWarning() {
        const alertCard = document.getElementById('phone-warning-alert');
        if (!alertCard) return;
        const savedPhone = localStorage.getItem('whatsappPhone');
        alertCard.style.display = (!savedPhone || savedPhone.trim() === '') ? 'flex' : 'none';
    }

    document.addEventListener('DOMContentLoaded', () => {
        initMotivation();
        checkPhoneNumberWarning();
        initWelcomeGreeting();
    });
</script>
