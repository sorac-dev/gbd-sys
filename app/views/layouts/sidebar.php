<?php
/**
 * Layout: Sidebar de navegación
 */
$appUrl      = rtrim(getenv('APP_URL') ?: '', '/');
$currentUrl  = $_GET['url'] ?? 'dashboard';

function sidebarActive(string $prefix, string $current): string {
    return (strpos($current, $prefix) === 0) ? 'active' : '';
}
?>
<aside class="app-sidebar" id="appSidebar">

    <!-- Logo área (visible en sidebar desktop) -->
    <div class="sidebar-header d-none d-lg-flex align-items-center gap-2 px-3 py-3">
        <div class="brand-icon brand-icon--sm">
            <i class="bi bi-credit-card-2-front-fill"></i>
        </div>
        <span class="fw-700 text-white fs-6">GDB Pagos</span>
    </div>

    <nav class="sidebar-nav">

        <!-- General -->
        <div class="sidebar-section-title">General</div>
        <ul class="sidebar-menu list-unstyled mb-0">

            <li>
                <a href="<?= $appUrl ?>/index.php?url=dashboard"
                   class="sidebar-link <?= sidebarActive('dashboard', $currentUrl) ?>" id="sidebarDashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="<?= $appUrl ?>/index.php?url=servicios"
                   class="sidebar-link <?= sidebarActive('servicios', $currentUrl) ?>" id="sidebarServicios">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <span>Servicios</span>
                </a>
            </li>

            <li>
                <a href="<?= $appUrl ?>/index.php?url=pagos"
                   class="sidebar-link <?= sidebarActive('pagos', $currentUrl) ?>" id="sidebarPagos">
                    <i class="bi bi-receipt"></i>
                    <span>Mis Pagos</span>
                </a>
            </li>

            <li>
                <a href="<?= $appUrl ?>/index.php?url=pagos/create"
                   class="sidebar-link <?= $currentUrl === 'pagos/create' ? 'active' : '' ?>" id="sidebarNuevoPago">
                    <i class="bi bi-plus-circle"></i>
                    <span>Nuevo Pago</span>
                </a>
            </li>

        </ul>

        <?php if (isAdmin()): ?>
        <!-- Admin -->
        <div class="sidebar-section-title mt-3">Administración</div>
        <ul class="sidebar-menu list-unstyled mb-0">

            <li>
                <a href="<?= $appUrl ?>/index.php?url=usuarios"
                   class="sidebar-link <?= sidebarActive('usuarios', $currentUrl) ?>" id="sidebarUsuarios">
                    <i class="bi bi-people"></i>
                    <span>Usuarios</span>
                </a>
            </li>

            <li>
                <a href="<?= $appUrl ?>/index.php?url=servicios/create"
                   class="sidebar-link <?= $currentUrl === 'servicios/create' ? 'active' : '' ?>" id="sidebarCrearServicio">
                    <i class="bi bi-plus-square"></i>
                    <span>Crear Servicio</span>
                </a>
            </li>

        </ul>
        <?php endif; ?>

    </nav>

    <!-- Perfil inferior -->
    <div class="sidebar-footer">
        <a href="<?= $appUrl ?>/index.php?url=usuarios/profile" class="sidebar-profile-link">
            <div class="user-avatar user-avatar--sm">
                <?= strtoupper(substr(currentUserName(), 0, 1)) ?>
            </div>
            <div class="overflow-hidden">
                <div class="text-white fw-500 text-truncate small"><?= htmlspecialchars(currentUserName()) ?></div>
                <div class="text-muted-sidebar small text-truncate"><?= isAdmin() ? 'Administrador' : 'Usuario' ?></div>
            </div>
        </a>
    </div>

</aside>
