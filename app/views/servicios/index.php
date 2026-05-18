<?php
/**
 * Vista: Listado de servicios
 */
$pageTitle = 'Servicios';
$appUrl    = rtrim(getenv('APP_URL') ?: '', '/');
require_once dirname(__DIR__, 2) . '/helpers/auth.php';
?>
<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="app-wrapper">
    <?php require_once dirname(__DIR__) . '/layouts/sidebar.php'; ?>
    <div class="app-main">
        <?php require_once dirname(__DIR__) . '/layouts/navbar.php'; ?>
        <main class="main-content">

            <div class="page-header">
                <div>
                    <h1 class="page-title">Servicios</h1>
                    <p class="page-subtitle">Catálogo de servicios disponibles</p>
                </div>
                <?php if (isAdmin()): ?>
                <a href="<?= $appUrl ?>/index.php?url=servicios/create"
                   class="btn btn-primary" id="createServicioBtn">
                    <i class="bi bi-plus-circle me-2"></i>Nuevo Servicio
                </a>
                <?php endif; ?>
            </div>

            <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert" id="serviciosFlash">
                <i class="bi bi-check-circle me-2"></i><?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
            <?php endif; ?>

            <?php if (empty($servicios)): ?>
            <div class="empty-state">
                <i class="bi bi-grid fs-1 text-muted"></i>
                <p class="text-muted mt-2">No hay servicios registrados.</p>
                <?php if (isAdmin()): ?>
                <a href="<?= $appUrl ?>/index.php?url=servicios/create" class="btn btn-primary" id="emptyServicioBtn">
                    <i class="bi bi-plus-circle me-2"></i>Crear Primer Servicio
                </a>
                <?php endif; ?>
            </div>
            <?php else: ?>

            <!-- Búsqueda / filtro cliente -->
            <div class="mb-4">
                <input type="text" id="searchServicios" class="form-control form-control-lg"
                       placeholder="&#xF52A;  Buscar servicio por nombre o categoría…"
                       style="font-family:'Inter', 'bootstrap-icons', sans-serif;">
            </div>

            <div class="row g-4" id="serviciosGrid">
                <?php
                $catColors = [
                    'Energía'   => 'warning',
                    'Agua'      => 'info',
                    'Telecom'   => 'primary',
                    'Municipal' => 'secondary',
                    'Seguros'   => 'success',
                ];
                $catIcons = [
                    'Energía'   => 'lightning-charge-fill',
                    'Agua'      => 'droplet-fill',
                    'Telecom'   => 'wifi',
                    'Municipal' => 'building',
                    'Seguros'   => 'shield-fill-check',
                ];
                foreach ($servicios as $s):
                    $color = $catColors[$s['categoria']] ?? 'primary';
                    $icon  = $catIcons[$s['categoria']]  ?? 'grid';
                ?>
                <div class="col-sm-6 col-lg-4 col-xl-3 servicio-item"
                     data-search="<?= strtolower(htmlspecialchars($s['nombre'] . ' ' . $s['categoria'])) ?>">
                    <div class="servicio-card <?= !(bool)$s['activo'] ? 'servicio-card--inactive' : '' ?>">
                        <div class="servicio-card__icon bg-<?= $color ?>-subtle text-<?= $color ?>">
                            <i class="bi bi-<?= $icon ?>"></i>
                        </div>
                        <div class="servicio-card__body">
                            <span class="badge bg-<?= $color ?>-subtle text-<?= $color ?> mb-1">
                                <?= htmlspecialchars($s['categoria']) ?>
                            </span>
                            <h3 class="servicio-card__name"><?= htmlspecialchars($s['nombre']) ?></h3>
                            <p class="servicio-card__desc text-muted small">
                                <?= htmlspecialchars(mb_substr($s['descripcion'] ?? '—', 0, 60)) ?>…
                            </p>
                            <div class="servicio-card__price">
                                $<?= number_format((float)$s['precio'], 2) ?>
                                <span class="text-muted small">/mes</span>
                            </div>
                            <?php if (!(bool)$s['activo']): ?>
                            <span class="badge bg-secondary mt-1">Inactivo</span>
                            <?php endif; ?>
                        </div>
                        <div class="servicio-card__actions">
                            <a href="<?= $appUrl ?>/index.php?url=servicios/show/<?= $s['id'] ?>"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Ver
                            </a>
                            <?php if ((bool)$s['activo']): ?>
                            <a href="<?= $appUrl ?>/index.php?url=pagos/create&servicio_id=<?= $s['id'] ?>"
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-credit-card"></i> Pagar
                            </a>
                            <?php endif; ?>
                            <?php if (isAdmin()): ?>
                            <a href="<?= $appUrl ?>/index.php?url=servicios/edit/<?= $s['id'] ?>"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= $appUrl ?>/index.php?url=servicios/delete/<?= $s['id'] ?>"
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('¿Eliminar este servicio?')"
                               id="deleteServicio<?= $s['id'] ?>">
                                <i class="bi bi-trash"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </main>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
