/**
 * GDB Pagos — app.js
 * Lógica de interfaz sin frameworks.
 */

'use strict';

document.addEventListener('DOMContentLoaded', () => {

    // ── Sidebar toggle (móvil) ─────────────────────────────────────────────
    initSidebar();

    // ── Toggle visibilidad de contraseña ──────────────────────────────────
    initPasswordToggle();

    // ── Búsqueda de servicios (listado) ───────────────────────────────────
    initSearchServicios();

    // ── Búsqueda de pagos ─────────────────────────────────────────────────
    initSearchPagos();

    // ── Búsqueda de usuarios ──────────────────────────────────────────────
    initSearchUsuarios();

    // ── Selector de método de pago ────────────────────────────────────────
    initPaymentMethods();

    // ── Resumen dinámico de pago ──────────────────────────────────────────
    initPaymentSummary();

    // ── Selector de nueva categoría ───────────────────────────────────────
    initNuevaCategoria();

    // ── Autocierre de alertas flash ───────────────────────────────────────
    initFlashAutoDismiss();

    // ── Filtro por estado en listado de pagos ─────────────────────────────
    initEstadoFilter();

});

/* ── Sidebar ──────────────────────────────────────────────────────────────── */
function initSidebar() {
    const sidebar        = document.getElementById('appSidebar');
    const toggleMobile   = document.getElementById('sidebarToggleMobile');
    if (!sidebar) return;

    // Crear overlay
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    overlay.id = 'sidebarOverlay';
    document.body.appendChild(overlay);

    function openSidebar() {
        sidebar.classList.add('is-open');
        overlay.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('is-open');
        overlay.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    if (toggleMobile) {
        toggleMobile.addEventListener('click', () => {
            sidebar.classList.contains('is-open') ? closeSidebar() : openSidebar();
        });
    }

    overlay.addEventListener('click', closeSidebar);
}

/* ── Contraseña ───────────────────────────────────────────────────────────── */
function initPasswordToggle() {
    document.querySelectorAll('[id^="togglePassword"]').forEach(btn => {
        btn.addEventListener('click', () => {
            // Buscar input de contraseña hermano
            const input = btn.closest('.input-group')?.querySelector('input[type="password"], input[type="text"]');
            if (!input) return;
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.querySelector('i').className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    });
}

/* ── Búsqueda de servicios ────────────────────────────────────────────────── */
function initSearchServicios() {
    const input = document.getElementById('searchServicios');
    const items = document.querySelectorAll('.servicio-item');
    if (!input || !items.length) return;

    input.addEventListener('input', () => {
        const q = input.value.toLowerCase().trim();
        items.forEach(item => {
            const text = item.dataset.search ?? '';
            item.style.display = (!q || text.includes(q)) ? '' : 'none';
        });
    });
}

/* ── Búsqueda y filtro de pagos ───────────────────────────────────────────── */
function initSearchPagos() {
    const input = document.getElementById('searchPagos');
    const rows  = document.querySelectorAll('.pago-row');
    const count = document.getElementById('pagosCount');
    if (!input || !rows.length) return;

    function filterPagos() {
        const q = (input.value ?? '').toLowerCase().trim();
        let visible = 0;
        rows.forEach(row => {
            const text   = row.dataset.search ?? '';
            const estado = row.dataset.estado ?? '';
            const estadoFilter = document.getElementById('filterEstado')?.value ?? '';
            const matchText   = !q || text.includes(q);
            const matchEstado = !estadoFilter || estado === estadoFilter;
            const show = matchText && matchEstado;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        if (count) count.textContent = `${visible} pago(s) encontrado(s)`;
    }

    input.addEventListener('input', filterPagos);

    // Botón limpiar
    const clearBtn = document.getElementById('clearFiltersBtn');
    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            input.value = '';
            const estadoSel = document.getElementById('filterEstado');
            if (estadoSel) estadoSel.value = '';
            filterPagos();
        });
    }
}

/* ── Filtro por estado ────────────────────────────────────────────────────── */
function initEstadoFilter() {
    const select = document.getElementById('filterEstado');
    if (!select) return;
    select.addEventListener('change', () => {
        // Dispara el mismo filtro de pagos
        document.getElementById('searchPagos')?.dispatchEvent(new Event('input'));
    });
}

/* ── Búsqueda de usuarios ─────────────────────────────────────────────────── */
function initSearchUsuarios() {
    const input = document.getElementById('searchUsuarios');
    const rows  = document.querySelectorAll('.usuario-row');
    if (!input || !rows.length) return;

    input.addEventListener('input', () => {
        const q = input.value.toLowerCase().trim();
        rows.forEach(row => {
            const text = row.dataset.search ?? '';
            row.style.display = (!q || text.includes(q)) ? '' : 'none';
        });
    });
}

/* ── Selector método de pago ──────────────────────────────────────────────── */
function initPaymentMethods() {
    const grid = document.getElementById('paymentMethodsGrid');
    if (!grid) return;

    grid.addEventListener('click', e => {
        const option = e.target.closest('.payment-method-option');
        if (!option) return;
        grid.querySelectorAll('.payment-method-option').forEach(o => o.classList.remove('selected'));
        option.classList.add('selected');
        const radio = option.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
    });
}

/* ── Resumen dinámico de pago ─────────────────────────────────────────────── */
function initPaymentSummary() {
    const servicioSel  = document.getElementById('servicio_id');
    const montoDisplay = document.getElementById('monto_display');
    const summaryBody  = document.getElementById('paymentSummaryBody');
    if (!servicioSel) return;

    function updateSummary() {
        const option = servicioSel.options[servicioSel.selectedIndex];
        if (!option || !option.value) {
            if (montoDisplay) montoDisplay.value = '';
            if (summaryBody) {
                summaryBody.innerHTML = `
                    <div class="empty-state py-4">
                        <i class="bi bi-grid text-muted fs-2"></i>
                        <p class="text-muted mt-2 small">Selecciona un servicio para ver el resumen</p>
                    </div>`;
            }
            return;
        }

        const precio = parseFloat(option.dataset.precio ?? 0).toFixed(2);
        const cat    = option.dataset.cat    ?? '—';
        const desc   = option.dataset.desc   ?? '';
        const nombre = option.text.split('—')[0].trim();

        if (montoDisplay) montoDisplay.value = precio;

        if (summaryBody) {
            summaryBody.innerHTML = `
                <dl class="row mb-3">
                    <dt class="col-5 text-muted small">Servicio</dt>
                    <dd class="col-7 fw-600 small">${escHtml(nombre)}</dd>
                    <dt class="col-5 text-muted small">Categoría</dt>
                    <dd class="col-7 small">${escHtml(cat)}</dd>
                    ${desc ? `<dt class="col-5 text-muted small">Descripción</dt><dd class="col-7 small">${escHtml(desc)}</dd>` : ''}
                </dl>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Total a pagar</span>
                    <span class="fs-3 fw-700 text-primary">$${precio}</span>
                </div>`;
        }
    }

    servicioSel.addEventListener('change', updateSummary);
    // Ejecutar al cargar si hay selección previa
    updateSummary();
}

/* ── Nueva categoría dinámica ─────────────────────────────────────────────── */
function initNuevaCategoria() {
    const catSel  = document.getElementById('categoria');
    const nuevaIn = document.getElementById('nuevaCategoria');
    if (!catSel || !nuevaIn) return;

    catSel.addEventListener('change', () => {
        if (catSel.value === '__nueva__') {
            nuevaIn.classList.remove('d-none');
            nuevaIn.required = true;
            nuevaIn.focus();
        } else {
            nuevaIn.classList.add('d-none');
            nuevaIn.required = false;
            nuevaIn.value = '';
        }
    });

    // Al enviar el form, copiar el valor de nuevaCategoria a categoría
    const form = catSel.closest('form');
    if (form) {
        form.addEventListener('submit', () => {
            if (catSel.value === '__nueva__' && nuevaIn.value.trim()) {
                catSel.value = nuevaIn.value.trim();
            }
        });
    }
}

/* ── Flash autocierre ─────────────────────────────────────────────────────── */
function initFlashAutoDismiss() {
    document.querySelectorAll('.alert.alert-success').forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert?.close();
        }, 4000);
    });
}

/* ── Utilidad: escapar HTML ───────────────────────────────────────────────── */
function escHtml(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(str));
    return d.innerHTML;
}
