(function () {
    /* ===============================
     * 1) Leer URLs desde <body>
     * =============================== */
    const urlsRaw = document.body?.dataset?.cartUrls;
    if (!urlsRaw) return;

    let URLS;
    try {
        URLS = JSON.parse(urlsRaw);
    } catch (e) {
        console.error('[cart.js] data-cart-urls inválido', e);
        return;
    }

    if (!URLS?.state || !URLS?.index || !URLS?.ajaxStore || !URLS?.ajaxDestroy) return;

    /* ===============================
     * 2) CSRF
     * =============================== */
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    /* ===============================
     * 3) DOM
     * =============================== */
    const iplCartButton = document.getElementById('iplCartButton');
    const iplCartDropdown = document.getElementById('iplCartDropdown');
    const iplCartClose = document.getElementById('iplCartClose');
    const iplCartBadge = document.getElementById('iplCartBadge');
    const iplCartItems = document.getElementById('iplCartItems');
    const iplCartSummary = document.getElementById('iplCartSummary');
    const iplCartTotal = document.getElementById('iplCartTotal');
    const iplCartFooter = document.getElementById('iplCartFooter');
    const iplCartRoot = document.getElementById('iplCart');
    const cartIcon = document.getElementById('cartIcon');

    if (!iplCartButton || !iplCartDropdown) return;

    let isOpen = false;
    let isAdding = false;
    let lastFetchId = 0;

    /* ===============================
     * 4) Utils
     * =============================== */
    function euro(n) {
        try {
            return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(n);
        } catch {
            return '€' + Number(n).toFixed(2);
        }
    }

    function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, m => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        }[m]));
    }

    function setOpen(open) {
        isOpen = open;
        iplCartDropdown.classList.toggle('hidden', !open);
        iplCartButton.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    /* ===============================
     * 5) Fetch state
     * =============================== */
    async function fetchCartState() {
        const fetchId = ++lastFetchId;
        iplCartSummary.textContent = 'Cargando…';

        const res = await fetch(URLS.state, {
            headers: { 'Accept': 'application/json' }
        });

        if (fetchId !== lastFetchId) return null;
        if (!res.ok) return null;

        return await res.json();
    }

    /* ===============================
     * 6) Render
     * =============================== */
    function renderCart(data) {
        const qty = data?.totalQuantity ?? 0;
        const total = data?.total ?? 0;
        const items = data?.items ?? [];

        iplCartBadge.textContent = qty;
        iplCartTotal.textContent = euro(total);

        if (iplCartFooter) {
            iplCartFooter.classList.toggle('hidden', items.length === 0);
        }

        if (items.length === 0) {
            iplCartSummary.textContent = 'Tu carrito está vacío.';
            iplCartItems.innerHTML =
                '<div class="text-sm text-gray-600">No hay productos todavía.</div>';
            return;
        }

        iplCartSummary.textContent = `${qty} artículo(s)`;

        iplCartItems.innerHTML = items.map(item => `
      <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
          <img
            src="${escapeHtml(item.image ?? '')}"
            alt="${escapeHtml(item.name ?? 'Producto')}"
            class="h-12 w-12 rounded-md object-cover bg-gray-100"
            loading="lazy"
            onerror="this.style.display='none'"
          />
          <div class="min-w-0">
            <div class="text-sm font-semibold text-gray-900 truncate">
              ${escapeHtml(item.name)}
            </div>
            <div class="text-xs text-gray-600">
              Cantidad: ${item.quantity}
            </div>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <div class="text-sm font-semibold text-gray-900">
            ${euro(item.subtotal)}
          </div>

          <button
            type="button"
            data-iplcart-remove="${item.id}"
            class="inline-flex items-center justify-center rounded-full border border-red-300 bg-white p-2 text-red-600 shadow-sm hover:bg-red-50 active:scale-95 transition"
            title="Eliminar"
            aria-label="Eliminar"
          >
            ✕
          </button>
        </div>
      </div>
    `).join('');
    }

    /* ===============================
     * 7) Abrir / cerrar
     * =============================== */
    iplCartButton.addEventListener('click', async () => {
        if (isOpen) return setOpen(false);
        setOpen(true);
        const data = await fetchCartState();
        if (data && isOpen) renderCart(data);
    });

    iplCartClose?.addEventListener('click', () => setOpen(false));

    document.addEventListener('click', (e) => {
        if (isOpen && iplCartRoot && !iplCartRoot.contains(e.target)) setOpen(false);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isOpen) setOpen(false);
    });

    /* ===============================
     * 8) Eliminar item (AJAX)
     * =============================== */
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-iplcart-remove]');
        if (!btn) return;

        const id = btn.dataset.iplcartRemove;
        btn.disabled = true;

        try {
            const url = URLS.ajaxDestroy.replace('__ID__', encodeURIComponent(id));

            const res = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (!res.ok) {
                window.location.href = URLS.index;
                return;
            }

            const data = await res.json();
            renderCart(data);
        } catch {
            window.location.href = URLS.index;
        }
    });

    /* ===============================
     * 9) Add to cart (AJAX + animación)
     * =============================== */
    document.addEventListener('submit', async (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;
        if (!form.hasAttribute('data-add-to-cart-form')) return;

        e.preventDefault();
        if (isAdding) return;
        isAdding = true;

        setOpen(true);
        iplCartSummary.textContent = 'Añadiendo…';

        try {
            const res = await fetch(URLS.ajaxStore, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: new FormData(form),
            });

            if (!res.ok) {
                window.location.href = URLS.index;
                return;
            }

            const data = await res.json();
            renderCart(data);
        } catch {
            window.location.href = URLS.index;
        } finally {
            isAdding = false;
        }
    }, true);

})();
