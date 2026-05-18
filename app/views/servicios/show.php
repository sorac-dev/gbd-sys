<?php
/**
 * Vista: Detalle de servicio
 */
$pageTitle = 'Detalle de Servicio';
$appUrl    = rtrim(getenv('APP_URL') ?: '', '/');
require_once dirname(__DIR__, 2) . '/helpers/auth.php';

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
$color = $catColors[$servicio['categoria']] ?? 'primary';
$icon  = $catIcons[$servicio['categoria']]  ?? 'grid';
?>
<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="app-wrapper">
    <?php require_once dirname(__DIR__) . '/layouts/sidebar.php'; ?>
    <div class="app-main">
        <?php require_once dirname(__DIR__) . '/layouts/navbar.php'; ?>
        <main class="main-content">

            <div class="page-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="brand-icon brand-icon--lg bg-<?= $color ?>-subtle text-<?= $color ?>">
                        <i class="bi bi-<?= $icon ?>"></i>
                    </div>
                    <div>
                        <h1 class="page-title mb-0"><?= htmlspecialchars($servicio['nombre']) ?></h1>
                        <span class="badge bg-<?= $color ?>">
                            <?= htmlspecialchars($servicio['categoria']) ?>
                        </span>
                        <?php if (!(bool)$servicio['activo']): ?>
                        <span class="badge bg-secondary ms-1">Inactivo</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <?php if ((bool)$servicio['activo']): ?>
                    <a href="<?= $appUrl ?>/index.php?url=pagos/create&servicio_id=<?= $servicio['id'] ?>"
                       class="btn btn-primary" id="payServicioBtn">
                        <i class="bi bi-credit-card me-2"></i>Pagar Ahora
                    </a>
                    <?php endif; ?>
                    <?php if (isAdmin()): ?>
                    <a href="<?= $appUrl ?>/index.php?url=servicios/edit/<?= $servicio['id'] ?>"
                       class="btn btn-outline-secondary" id="editServicioFromShowBtn">
                        <i class="bi bi-pencil me-2"></i>Editar
                    </a>
                    <?php endif; ?>
                    <a href="<?= $appUrl ?>/index.php?url=servicios" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="card-custom h-100">
                        <div class="card-custom-header">
                            <h2 class="card-custom-title"><i class="bi bi-info-circle me-2"></i>Información</h2>
                        </div>
                        <div class="card-custom-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Nombre</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($servicio['nombre']) ?></dd>
                                <dt class="col-sm-4">Categoría</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-<?= $color ?>-subtle text-<?= $color ?>">
                                        <?= htmlspecialchars($servicio['categoria']) ?>
                                    </span>
                                </dd>
                                <dt class="col-sm-4">Descripción</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($servicio['descripcion'] ?? '—') ?></dd>
                                <dt class="col-sm-4">Estado</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-<?= (bool)$servicio['activo'] ? 'success' : 'secondary' ?>">
                                        <?= (bool)$servicio['activo'] ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </dd>
                                <dt class="col-sm-4">Creado</dt>
                                <dd class="col-sm-8">
                                    <?= date('d/m/Y H:i', strtotime($servicio['created_at'])) ?>
                                </dd>
                                <dt class="col-sm-4">Actualizado</dt>
                                <dd class="col-sm-8">
                                    <?= date('d/m/Y H:i', strtotime($servicio['updated_at'])) ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-custom text-center h-100 d-flex flex-column align-items-center justify-content-center p-4">
                        <p class="text-muted small mb-1">Precio mensual</p>
                        <div class="display-4 fw-700 text-<?= $color ?>">
                            $<?= number_format((float)$servicio['precio'], 2) ?>
                        </div>
                        <p class="text-muted small">/mes</p>
                        <?php if ((bool)$servicio['activo']): ?>
                        <a href="<?= $appUrl ?>/index.php?url=pagos/create&servicio_id=<?= $servicio['id'] ?>"
                           class="btn btn-primary mt-3 w-100" id="showPayNowBtn">
                            <i class="bi bi-credit-card me-2"></i>Pagar Ahora
                        </a>
                        <?php else: ?>
                        <div class="alert alert-secondary mt-3 w-100 small">
                            Servicio temporalmente no disponible.
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
