<div id="deleteModal" class="fixed inset-0 z-50 hidden" aria-labelledby="deleteModalTitle" role="dialog" aria-modal="true">

    {{-- Overlay --}}
    <div id="deleteModalOverlay" class="fixed inset-0 bg-gray-900/50"></div>

    {{-- Panel --}}
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-xl ring-1 ring-black/5 overflow-hidden">

            {{-- HEADER --}}
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-200">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-50 text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="h-5 w-5">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                        <path d="M10 11v6" />
                        <path d="M14 11v6" />
                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                    </svg>
                </div>
                <h2 id="deleteModalTitle" class="text-lg font-semibold text-gray-900">Confirmación</h2>
            </div>

            {{-- BODY --}}
            <div class="px-6 py-5">
                <p class="text-sm text-gray-600 mb-4">
                    Vas a eliminar el producto
                </p>
                <div class="flex items-center gap-4">
                    <img id="deleteProductImage" src="" alt="" class="h-16 w-16 rounded-md object-cover bg-gray-100 hidden" />
                    <span id="deleteProductName" class="font-semibold text-gray-900"></span>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="flex items-center justify-end gap-2 border-t border-gray-200 px-6 py-4">
                <button type="button" id="deleteCancelBtn" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancelar</button>
                <button type="button" id="deleteConfirmBtn" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-red-700" data-block-ui-confirm>Sí, eliminar</button>
            </div>
        </div>
    </div>
</div>
<script>
    (function() {
        const modal = document.getElementById('deleteModal');
        const overlay = document.getElementById('deleteModalOverlay');
        const cancelBtn = document.getElementById('deleteCancelBtn');
        const confirmBtn = document.getElementById('deleteConfirmBtn');
        const nameSpan = document.getElementById('deleteProductName');
        const imgEl = document.getElementById('deleteProductImage');

        let formToSubmit = null;

        function openModal(productName, imgSrc, formEl) {
            formToSubmit = formEl;

            nameSpan.textContent = productName || '';

            if (imgEl) {
                if (imgSrc) {
                    imgEl.src = imgSrc;
                    imgEl.alt = productName ? `Imagen de ${productName}` : 'Imagen del producto';
                    imgEl.classList.remove('hidden');
                } else {
                    imgEl.src = '';
                    imgEl.alt = '';
                    imgEl.classList.add('hidden');
                }
            }

            modal.classList.remove('hidden');
            confirmBtn.focus();
            document.addEventListener('keydown', onKeyDown);
        }

        function closeModal() {
            modal.classList.add('hidden');
            formToSubmit = null;

            // limpia imagen por si abres otro modal luego
            if (imgEl) {
                imgEl.src = '';
                imgEl.alt = '';
                imgEl.classList.add('hidden');
            }

            document.removeEventListener('keydown', onKeyDown);
        }

        function onKeyDown(e) {
            if (e.key === 'Escape') closeModal();
        }

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('[data-delete-button]');
            if (!btn) return;

            e.preventDefault();

            const productName = btn.getAttribute('data-product-name') || '';
            const form = btn.closest('form');
            if (!form) return;

            // ✅ coge la imagen de la fila (tu markup actual ya la tiene)
            const row = btn.closest('tr');
            const imgSrc = row?.querySelector('td img')?.getAttribute('src') || '';

            openModal(productName, imgSrc, form);
        });

        overlay.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        confirmBtn.addEventListener('click', function() {
            if (formToSubmit) formToSubmit.submit();
        });
    })();
</script>