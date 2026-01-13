(function () {
    // URLs desde <body data-cart-urls="...">
    const urlsRaw = document.body?.dataset?.cartUrls;
    if (!urlsRaw) return;

    let URLS;
    try { URLS = JSON.parse(urlsRaw); }
    catch (e) { console.error('[cart.js] data-cart-urls inválido', e); return; }

    if (!URLS?.state || !URLS?.index || !URLS?.ajaxStore || !URLS?.ajaxDestroy) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

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

    if (!iplCartButton || !iplCartDropdown || !iplCartBadge || !iplCartItems || !iplCartSummary || !iplCartTotal) return;

    let isOpen = false;
    let isAdding = false;
    let lastFetchId = 0;

    /* ---------------- Utils ---------------- */
    function euro(n) {
        try { return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(n); }
        catch { return '€' + Number(n).toFixed(2); }
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

    /* ---------------- Overlay blocker (UI bloqueada) ---------------- */
    function ensureBlocker() {
        let el = document.getElementById('iplCartBlocker');
        if (el) return el;

        el = document.createElement('div');
        el.id = 'iplCartBlocker';
        el.className = 'fixed inset-0 z-[9998] hidden items-center justify-center bg-black/40';
        el.innerHTML = `
    <div class="flex items-center gap-3 rounded-2xl bg-white/90 px-5 py-4 shadow-xl">
      <div class="h-5 w-5 animate-spin rounded-full border-2 border-gray-300 border-t-gray-800"></div>
      <div id="iplCartBlockerText" class="text-sm font-medium text-gray-800">Procesando...</div>
    </div>
  `;
        document.body.appendChild(el);
        return el;
    }

    function setBlockerText(text) {
        const el = document.getElementById('iplCartBlockerText');
        if (el) el.textContent = text;
    }

    function blockUI(on) {
        const el = ensureBlocker();
        el.classList.toggle('hidden', !on);
        el.classList.toggle('flex', on);
        document.documentElement.classList.toggle('overflow-hidden', on);
    }

    /* ---------------- Scroll arriba (Promise) ---------------- */
    function scrollToTop() {
        return new Promise((resolve) => {
            const start = Date.now();
            window.scrollTo({ top: 0, behavior: 'smooth' });

            (function check() {
                const nearTop = window.scrollY <= 2;
                const timedOut = (Date.now() - start) > 1200;
                if (nearTop || timedOut) return resolve();
                requestAnimationFrame(check);
            })();
        });
    }

    /* ---------------- Fetch state ---------------- */
    async function fetchCartState() {
        const fetchId = ++lastFetchId;
        iplCartSummary.textContent = 'Cargando…';

        const res = await fetch(URLS.state, { headers: { 'Accept': 'application/json' } });

        if (fetchId !== lastFetchId) return null;
        if (!res.ok) { iplCartSummary.textContent = 'No se pudo cargar el carrito.'; return null; }

        return await res.json();
    }

    /* ---------------- Render ---------------- */
    function renderCart(data) {
        const qty = data?.totalQuantity ?? 0;
        const total = data?.total ?? 0;
        const items = data?.items ?? [];

        iplCartBadge.textContent = qty;
        iplCartTotal.textContent = euro(total);

        if (iplCartFooter) iplCartFooter.classList.toggle('hidden', items.length === 0);

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
            src="${escapeHtml(item.image ?? '')}"
            alt="${escapeHtml(item.name ?? 'Producto')}"
            class="h-12 w-12 rounded-md object-cover bg-gray-100"
            loading="lazy"
            onerror="this.style.display='none'"
          />
          <div class="min-w-0">
            <div class="text-sm font-semibold text-gray-900 truncate">${escapeHtml(item.name ?? 'Producto')}</div>
            <div class="text-xs text-gray-600">Cantidad: ${item.quantity ?? 0}</div>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <div class="text-sm font-semibold text-gray-900">${euro(item.subtotal ?? 0)}</div>
          <button type="button" data-iplcart-remove="${item.id}"
            class="inline-flex items-center justify-center rounded-full border border-red-300 bg-white p-2 text-red-600 shadow-sm hover:bg-red-50 active:scale-95 transition"
            title="Eliminar" aria-label="Eliminar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2 m-7 0h8m-9 3v7a2 2 0 002 2h4 a2 2 0 002-2v-7" />
                </svg>
            </button>
        </div>
      </div>
    `).join('');
    }

    /* ---------------- Animación: clonar imagen -> carrito ---------------- */
    function createFlyingCloneFromCard(cardEl) {
        if (!cardEl) return null;

        const imgWrapper = cardEl.querySelector('[data-product-image]');
        const img = imgWrapper?.querySelector('img, picture img');
        const source = img || imgWrapper;
        if (!source) return null;

        const rect = source.getBoundingClientRect();
        if (rect.width === 0 || rect.height === 0) return null;

        let clone;
        if (img) {
            clone = img.cloneNode(true);
        } else {
            clone = document.createElement('div');
            clone.style.background = 'rgba(0,0,0,.08)';
        }

        clone.style.position = 'fixed';
        clone.style.left = rect.left + 'px';
        clone.style.top = rect.top + 'px';
        clone.style.width = rect.width + 'px';
        clone.style.height = rect.height + 'px';
        clone.style.zIndex = 9999;
        clone.style.pointerEvents = 'none';
        clone.style.borderRadius = '12px';
        clone.style.objectFit = 'cover';
        clone.style.transition = 'transform 700ms cubic-bezier(.2,.8,.2,1), opacity 700ms';

        document.body.appendChild(clone);
        return clone;
    }

    function animateCloneToCart(clone) {
        return new Promise((resolve) => {
            if (!clone || !cartIcon) return resolve();

            const end = cartIcon.getBoundingClientRect();
            if (end.width === 0 || end.height === 0) { clone.remove(); return resolve(); }

            const start = clone.getBoundingClientRect();

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
                resolve();
            }, { once: true });
        });
    }

    function isMobile() {
        return window.matchMedia('(max-width: 767px)').matches;
    }

    function ensureToast() {
        let el = document.getElementById('iplCartToast');
        if (el) return el;

        el = document.createElement('div');
        el.id = 'iplCartToast';
        el.className = 'fixed left-1/2 top-4 z-[10000] hidden -translate-x-1/2 rounded-2xl bg-gray-900/90 px-4 py-3 text-sm text-white shadow-xl';
        el.setAttribute('role', 'status');
        el.setAttribute('aria-live', 'polite');
        el.addEventListener('click', (e) => {
            const a = e.target.closest('a');
            if (a) el.classList.add('hidden');
        });

        document.body.appendChild(el);

        return el;
    }

    let toastTimer = null;

    function showToast({ message, ms = 2000, actionText = null, actionHref = null } = {}) {
        const el = ensureToast();

        // Contenido
        const safeMsg = String(message ?? '');
        if (actionText && actionHref) {
            el.innerHTML = `
                <div class="flex items-center gap-3">
                    <span class="font-medium">${safeMsg}</span>
                    <a href="${actionHref}" class="ml-1 inline-flex items-center rounded-lg bg-white/10 px-3 py-1.5 text-sm font-semibold text-white hover:bg-white/20 active:scale-[0.98] transition">
                    ${actionText}
                    </a>
                </div>
                `;
        } else {
            el.textContent = safeMsg;
        }

        el.classList.remove('hidden');

        // Autocierre (si ms == 0, no autocierra)
        if (toastTimer) clearTimeout(toastTimer);
        if (ms > 0) {
            toastTimer = setTimeout(() => el.classList.add('hidden'), ms);
        }
    }


    function isCartIconVisible() {
        if (!cartIcon) return false;
        const r = cartIcon.getBoundingClientRect();
        return r.width > 0 && r.height > 0 && r.top >= 0 && r.bottom <= window.innerHeight;
    }

    // Scroll arriba (Promise)
    function scrollToTop() {
        return new Promise((resolve) => {
            const start = Date.now();
            window.scrollTo({ top: 0, behavior: 'smooth' });

            (function check() {
                const nearTop = window.scrollY <= 2;
                const timedOut = (Date.now() - start) > 1200;
                if (nearTop || timedOut) return resolve();
                requestAnimationFrame(check);
            })();
        });
    }


    /* ---------------- Abrir/cerrar mini carrito ---------------- */
    iplCartButton.addEventListener('click', async () => {
        if (isOpen) return setOpen(false);
        if (!isMobile()) {
            setOpen(true);
        }
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

    /* ---------------- Eliminar item (AJAX) ---------------- */
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-iplcart-remove]');
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation();

        const id = btn.dataset.iplcartRemove;
        if (!id) return;

        setBlockerText('Eliminando ...');
        // 🔒 bloquear UI
        blockUI(true);

        btn.disabled = true;
        btn.classList.add('opacity-60', 'cursor-not-allowed');

        try {
            const url = URLS.ajaxDestroy.replace('__ID__', encodeURIComponent(id));

            const fd = new FormData();
            fd.append('_token', csrf);
            fd.append('_method', 'DELETE');

            const res = await fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: fd,
            });

            if (!res.ok) {
                console.error('[iplCart] delete failed', res.status, await res.text());
                iplCartSummary.textContent = 'No se pudo eliminar. Reintenta.';
                return;
            }

            const data = await res.json();
            renderCart(data);

        } catch (err) {
            console.error('[iplCart] delete exception', err);
            iplCartSummary.textContent = 'No se pudo eliminar. Reintenta.';
        } finally {
            setBlockerText('Procesando ...');
            // 🔓 desbloquear UI
            blockUI(false);
            btn.disabled = false;
            btn.classList.remove('opacity-60', 'cursor-not-allowed');
        }
    });


    /* ---------------- ADD TO CART (quick-action incluido) ---------------- */
    document.addEventListener('submit', async (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;
        if (!form.hasAttribute('data-add-to-cart-form')) return;

        e.preventDefault();
        if (isAdding) return;
        isAdding = true;

        const mobile = isMobile();
        const iconVisible = isCartIconVisible();

        // En desktop: si no se ve el icono, haremos scroll arriba
        const shouldScroll = !mobile && !iconVisible;

        // 1) crear clone ANTES de hacer scroll (para que mantenga el origen)
        const card = form.closest('[data-product-card]');
        const clone = createFlyingCloneFromCard(card);

        // 2) bloquear UI y abrir carrito
        setBlockerText('Añadiendo ...');
        blockUI(true);
        if (!mobile) {
            setOpen(true);
        }
        iplCartSummary.textContent = 'Añadiendo…';

        // 3) arrancar scroll arriba y request en paralelo
        const scrollP = shouldScroll ? scrollToTop() : Promise.resolve();

        // hacemos request en paralelo
        const addP = (async () => {
            const res = await fetch(URLS.ajaxStore, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: new FormData(form),
            });

            if (!res.ok) throw new Error('Add failed');
            return await res.json();
        })();

        try {
            await scrollP;

            // En mobile: no animamos (porque igual el header ni está “pensado” para verse)
            // En desktop: animamos siempre que tengamos clone + icono
            const animP = (!mobile) ? animateCloneToCart(clone) : Promise.resolve();

            const data = await addP;
            renderCart(data);
            if (!mobile) {
                setOpen(true);
            }

            // Feedback UX
            if (mobile) {
                showToast({
                    message: '✅ Producto añadido',
                    ms: 2500,
                    actionText: 'Ver carrito',
                    actionHref: URLS.index
                });
            } else if (shouldScroll) {
                // al hacer scroll + ver carrito abierto, basta un toast corto (opcional)
                showToast('✅ Producto añadido', 1200);
            }

            await animP;
        } catch (err) {
            window.location.href = URLS.index;
        } finally {
            setBlockerText('Procesando…');
            blockUI(false);
            isAdding = false;
        }

    }, true);

})();
