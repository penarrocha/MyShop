@props([
'id' => 'pageBlocker',
'text' => null,
'formAttr' => 'data-block-ui',
'confirmAttr' => 'data-block-ui-confirm'
])

<div id="{{ $id }}" class="fixed inset-0 z-[60] hidden" aria-hidden="true">
    <div class="absolute inset-0 bg-gray-900/40"></div>

    <div class="absolute inset-0 flex items-center justify-center">
        <div class="flex items-center gap-3 rounded-2xl bg-white/90 px-5 py-4 shadow-xl ring-1 ring-black/5">
            <svg class="h-5 w-5 animate-spin text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4z"></path>
            </svg>
            @if ($text !== null)
            <span class="text-sm font-medium text-gray-800">{{ $text }}</span>
            @endif
        </div>
    </div>
</div>
<script>
    (function() {
        const blocker = document.getElementById('{{ $id }}');
        if (!blocker) return;

        const FORM_ATTR = '{{ $formAttr }}';
        const CONFIRM_ATTR = '{{ $confirmAttr }}';

        let active = false;

        function show() {
            if (active) return;
            active = true;

            blocker.classList.remove('hidden');
            document.documentElement.classList.add('overflow-hidden');
        }

        function disableSubmitters(form) {
            const btns = form.querySelectorAll('button, input[type="submit"]');
            btns.forEach(btn => {
                btn.disabled = true;
                btn.classList.add('opacity-60', 'cursor-not-allowed');
            });
        }

        // Submits normales (PUT / DELETE / POST)
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (!(form instanceof HTMLFormElement)) return;
            if (!form.hasAttribute(FORM_ATTR)) return;

            disableSubmitters(form);
            show();
        }, true);

        // Botones que hacen submit manual (ej. confirmar modal)
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('[' + CONFIRM_ATTR + ']');
            if (!btn) return;

            show();
        }, true);

        // Si el navegador restaura la página (back/forward cache)
        window.addEventListener('pageshow', function() {
            blocker.classList.add('hidden');
            document.documentElement.classList.remove('overflow-hidden');
            active = false;
        });
    })();
</script>