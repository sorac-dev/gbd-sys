<?php
/**
 * Layout: Navbar superior
 */
$appName = getenv('APP_NAME') ?: 'GDB Pagos';
$appUrl  = rtrim(getenv('APP_URL') ?: '', '/');
?>
<nav class="navbar navbar-expand-lg app-navbar" id="mainNavbar">
    <div class="container-fluid px-3">

        <!-- Botón para colapsar sidebar en móvil -->
        <button class="btn btn-icon me-2 d-lg-none" id="sidebarToggleMobile" aria-label="Menú">
            <i class="bi bi-list fs-4"></i>
        </button>

        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= $appUrl ?>/index.php?url=dashboard">
            <div class="brand-icon">
                <i class="bi bi-credit-card-2-front-fill"></i>
            </div>
            <span class="fw-700"><?= htmlspecialchars($appName) ?></span>
        </a>

        <!-- Spacer -->
        <div class="flex-grow-1"></div>

        <!-- Acciones derecha -->
        <div class="d-flex align-items-center gap-2">

            <!-- Nuevo pago rápido -->
            <a href="<?= $appUrl ?>/index.php?url=pagos/create"
               class="btn btn-primary btn-sm d-none d-md-flex align-items-center gap-1" id="navBtnNewPayment">
                <i class="bi bi-plus-circle"></i> Nuevo Pago
            </a>

            <!-- Perfil dropdown -->
            <div class="dropdown">
                <button class="btn btn-icon dropdown-toggle no-caret d-flex align-items-center gap-2"
                        type="button" id="userMenuBtn"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar">
                        <?= strtoupper(substr(currentUserName(), 0, 1)) ?>
                    </div>
                    <span class="d-none d-md-inline text-truncate" style="max-width:140px;">
                        <?= htmlspecialchars(currentUserName()) ?>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userMenuBtn">
                    <li>
                        <span class="dropdown-item-text small text-muted">
                            <?= htmlspecialchars($_SESSION['usuario_email'] ?? '') ?>
                        </span>
                    </li>
                    <li>
                        <span class="dropdown-item-text small">
                            <span class="badge <?= isAdmin() ? 'bg-danger' : 'bg-primary' ?>">
                                <?= isAdmin() ? 'Administrador' : 'Usuario' ?>
                            </span>
                        </span>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="<?= $appUrl ?>/index.php?url=usuarios/profile">
                            <i class="bi bi-person me-2"></i>Mi Perfil
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= $appUrl ?>/index.php?url=pagos">
                            <i class="bi bi-receipt me-2"></i>Mis Pagos
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="<?= $appUrl ?>/index.php?url=auth/logout">
                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
