(function () {
    function initPicker(root) {
        // Evita inicializar dos veces el mismo componente
        if (root.dataset.imagePickerInit === '1') return;
        root.dataset.imagePickerInit = '1';

        const input = root.querySelector('[data-image-picker-input]');
        const preview = root.querySelector('[data-image-picker-preview]');
        const trash = root.querySelector('[data-image-picker-trash]');
        const flag = root.querySelector('[data-image-picker-remove-flag]');

        if (!input || !preview || !trash || !flag) return;

        const hasRealOriginal = root.dataset.hasRealImage === '1';

        const hasSelection = () => !!(input.files && input.files.length > 0);
        const isUnlinked = () => root.dataset.unlinked === '1';

        const setUnlinked = (v) => {
            root.dataset.unlinked = v ? '1' : '0';
            flag.value = v ? '1' : '0';
        };

        const originalSrc = () => preview.dataset.originalSrc;
        const defaultSrc = () => preview.dataset.defaultSrc;

        const setPreview = (url) => {
            preview.src = url;
        };

        const updateTrashVisibility = () => {
            const show = hasSelection() || (hasRealOriginal && !isUnlinked());
            trash.classList.toggle('hidden', !show);
        };

        // Estado inicial
        updateTrashVisibility();

        // Cambio de archivo
        input.addEventListener('change', () => {
            const file = input.files?.[0];

            if (!file) {
                // sin selección -> vuelve a original si existe y no está desvinculado; si no, default
                setPreview((hasRealOriginal && !isUnlinked()) ? originalSrc() : defaultSrc());
                updateTrashVisibility();
                return;
            }

            if (!file.type || !file.type.startsWith('image/')) {
                input.value = '';
                setPreview((hasRealOriginal && !isUnlinked()) ? originalSrc() : defaultSrc());
                updateTrashVisibility();
                return;
            }

            // selección nueva => cancela desvinculación
            setUnlinked(false);

            // preview
            const url = URL.createObjectURL(file);
            setPreview(url);

            // liberar URL cuando cambie de nuevo
            input.addEventListener('change', () => URL.revokeObjectURL(url), { once: true });

            updateTrashVisibility();
        });

        // Click papelera
        trash.addEventListener('click', () => {
            if (hasSelection()) {
                // Cancelar selección
                input.value = '';
                // restauramos original (si había) o default
                setPreview(hasRealOriginal ? originalSrc() : defaultSrc());
                updateTrashVisibility();
                return;
            }

            // No hay selección
            if (hasRealOriginal && !isUnlinked()) {
                // Desvincular original
                setUnlinked(true);
                setPreview(defaultSrc());
                updateTrashVisibility(); // ocultamos
            }
        });
    }

    function safeInitAll(scope = document) {
        scope.querySelectorAll('[data-image-picker]').forEach(initPicker);
    }

    // Carga normal
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => safeInitAll());
    } else {
        safeInitAll();
    }

    // restaurar la página (bfcache)
    window.addEventListener('pageshow', () => safeInitAll());

    // API pública (por si a futuro se inserta vía ajax o livewire)
    window.ImagePicker = { initAll: safeInitAll };

})();
