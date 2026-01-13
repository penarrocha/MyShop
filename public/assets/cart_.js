document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-iplcart-remove]');
    if (!btn) return;

    const id = btn.getAttribute('data-iplcart-remove');
    if (!id) return;

    // feedback
    btn.disabled = true;
    btn.classList.add('opacity-60', 'cursor-not-allowed');

    try {
        const fd = new FormData();
        fd.append('_method', 'DELETE'); // Laravel method spoofing

        const res = await fetch(`${URLS.cart.itemBase}/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: fd,
        });

        if (!res.ok) {
            window.location.href = URLS.cart.index;
            return;
        }

        // Ideal: que CartController@destroy devuelva state() si wantsJson()
        const data = await res.json();
        renderCart(data);
    } catch (err) {
        window.location.href = URLS.cart.index;
    }
});


(function () {

    const urlsRaw = document.body?.dataset?.cartUrls;
    if (!urlsRaw) return;

    let URLS;
    try {
        URLS = JSON.parse(urlsRaw);
    } catch (e) {
        console.error('[cart.js] data-cart-urls inválido', e);
        return;
    }

    // Guard: si no existe URLS, salimos
    if (!URLS?.state) return;


    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const iplCartButton = document.getElementById('iplCartButton');
    const iplCartDropdown = document.getElementById('iplCartDropdown');
    const iplCartClose = document.getElementById('iplCartClose');
    const iplCartBadge = document.getElementById('iplCartBadge');
    const iplCartItems = document.getElementById('iplCartItems');
    const iplCartTotal = document.getElementById('iplCartTotal');
    const iplCartSummary = document.getElementById('iplCartSummary');
    const iplCartFooter = document.getElementById('iplCartFooter');
    const cartIcon = document.getElementById('cartIcon');
    const iplCartRoot = document.getElementById('iplCart');

    if (!iplCartButton || !iplCartDropdown) return;

    let isOpen = false;
    let isAdding = false;
    let lastFetchId = 0;

    function euro(n) {
        try {
            return new Intl.NumberFormat('es-ES', {
                style: 'currency',
                currency: 'EUR'
            }).format(n);
        } catch {
            return '€' + Number(n).toFixed(2);
        }
    }

    function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, m => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[m]));
    }

    function setOpen(open) {
        isOpen = open;
        iplCartDropdown.classList.toggle('hidden', !open);
        iplCartButton.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    async function fetchCartState() {
        const fetchId = ++lastFetchId;

        iplCartSummary.textContent = 'Cargando…';
        const res = await fetch(URLS.cart.state, {
            headers: { 'Accept': 'application/json' }
        });

        if (fetchId !== lastFetchId) return null;

        if (!res.ok) {
            iplCartSummary.textContent = 'No se pudo cargar el carrito.';
            return null;
        }

        return await res.json();
    }




    function renderCart(data) {
        const qty = data?.totalQuantity ?? 0;
        const total = data?.total ?? 0;
        const items = data?.items ?? [];

        iplCartBadge.textContent = qty;
        iplCartTotal.textContent = euro(total);

        // ✅ Ocultar/mostrar footer según haya items
        if (iplCartFooter) {
            iplCartFooter.classList.toggle('hidden', items.length === 0);
        }

        if (items.length === 0) {
            iplCartSummary.textContent = 'Tu carrito está vacío.';
            iplCartItems.innerHTML = '<div class="text-sm text-gray-600">No hay productos todavía.</div>';
            return;
        }

        iplCartSummary.textContent = `${qty} artículo(s)`;

        iplCartItems.innerHTML = items.map(item => `
    <div class="flex items-center justify-between gap-3">
      <div class="flex items-center gap-3 min-w-0">
        <img
          src="${escapeHtml(item.image_url ?? '')}"
          alt="${escapeHtml(item.name ?? 'Producto')}"
          class="h-12 w-12 rounded-md object-cover bg-gray-100"
          loading="lazy"
          onerror="this.style.display='none'"
        />
        <div class="min-w-0">
          <div class="text-sm font-semibold text-gray-900 truncate">
            ${escapeHtml(item.name ?? 'Producto')}
          </div>
          <div class="text-xs text-gray-600">
            Cantidad: ${item.quantity ?? 0}
          </div>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <div class="text-sm font-semibold text-gray-900">
          ${euro(item.subtotal ?? 0)}
        </div>

        <button
          type="button"
          class="inline-flex items-center justify-center rounded-full border border-red-300 bg-white p-2 text-red-600 shadow-sm hover:bg-red-50 active:scale-95 transition"
          aria-label="Eliminar"
          title="Eliminar"
          data-iplcart-remove="${item.id}"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round"
            class="h-4 w-4">
            <polyline points="3 6 5 6 21 6" />
            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
            <path d="M10 11v6" />
            <path d="M14 11v6" />
            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
          </svg>
        </button>
      </div>
    </div>
  `).join('');
    }




    function renderCartOld(data) {
        const qty = data?.totalQuantity ?? 0;
        const total = data?.total ?? 0;
        const items = data?.items ?? [];

        iplCartBadge.textContent = qty;
        iplCartTotal.textContent = euro(total);

        if (iplCartFooter) {
            iplCartFooter.classList.toggle('hidden', items.length === 0);
        }

        if (items.length === 0) {
            iplCartSummary.textContent = '';
            iplCartItems.innerHTML =
                '<div class="text-sm text-gray-600">Nada por aquí</div>';
            return;
        }

        iplCartSummary.textContent = `${qty} artículo(s)`;

        iplCartItems.innerHTML = items.map(item => `
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <div class="text-sm font-semibold text-gray-900 truncate">
                        ${escapeHtml(item.name ?? 'Producto')}
                    </div>
                    <div class="text-xs text-gray-600">
                        Cantidad: ${item.quantity ?? 0}
                    </div>
                </div>
                <div class="text-sm font-semibold text-gray-900">
                    ${euro(item.subtotal ?? 0)}
                </div>
            </div>
        `).join('');
    }

    // Toggle open/close
    iplCartButton.addEventListener('click', async () => {
        if (isOpen) return setOpen(false);

        setOpen(true);
        const data = await fetchCartState();
        if (data && isOpen) renderCart(data);
    });

    iplCartClose?.addEventListener('click', () => setOpen(false));

    document.addEventListener('click', (e) => {
        if (!isOpen) return;
        if (iplCartRoot && !iplCartRoot.contains(e.target)) setOpen(false);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isOpen) setOpen(false);
    });

    function flyToCart(fromEl) {
        if (!fromEl || !cartIcon) return;

        const img = (fromEl.tagName === 'IMG') ? fromEl : fromEl.querySelector('img');
        const source = img || fromEl;

        const start = source.getBoundingClientRect();
        const end = cartIcon.getBoundingClientRect();

        if (start.width === 0 || start.height === 0) return;

        let clone;
        if (img) {
            clone = img.cloneNode(true);
        } else {
            clone = document.createElement('div');
            clone.style.background = 'rgba(0,0,0,.08)';
        }

        clone.style.position = 'fixed';
        clone.style.left = start.left + 'px';
        clone.style.top = start.top + 'px';
        clone.style.width = start.width + 'px';
        clone.style.height = start.height + 'px';
        clone.style.zIndex = 9999;
        clone.style.pointerEvents = 'none';
        clone.style.borderRadius = '12px';
        clone.style.transition = 'transform 700ms cubic-bezier(.2,.8,.2,1), opacity 700ms';
        clone.style.transformOrigin = 'center';
        clone.style.objectFit = 'cover';

        document.body.appendChild(clone);

        const dx = (end.left + end.width / 2) - (start.left + start.width / 2);
        const dy = (end.top + end.height / 2) - (start.top + start.height / 2);

        requestAnimationFrame(() => {
            clone.style.transform = `translate(${dx}px, ${dy}px) scale(0.2)`;
            clone.style.opacity = '0.2';
        });

        clone.addEventListener('transitionend', () => {
            clone.remove();
            iplCartBadge?.classList.add('scale-110');
            setTimeout(() => iplCartBadge?.classList.remove('scale-110'), 150);
        }, { once: true });
    }

    // Interceptar "add to cart"
    document.addEventListener('submit', async (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;
        if (!form.hasAttribute('data-add-to-cart-form')) return;

        e.preventDefault();
        if (isAdding) return;
        isAdding = true;

        setOpen(true);
        iplCartSummary.textContent = 'Añadiendo…';

        const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
        }

        const card = form.closest('[data-product-card]');
        const imgEl = card?.querySelector('[data-product-image]');
        if (imgEl) flyToCart(imgEl);

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: new FormData(form),
            });

            if (!res.ok) {
                window.location.href = URLS.cart.index;
                return;
            }

            const data = await res.json();
            renderCart(data);

        } catch {
            window.location.href = URLS.cart.index;
        } finally {
            isAdding = false;
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-60', 'cursor-not-allowed');
            }
        }
    }, true);

})();
