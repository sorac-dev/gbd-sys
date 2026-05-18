<?php
/**
 * Vista: Detalle de un pago
 */
$pageTitle = 'Detalle de Pago';
$appUrl    = rtrim(getenv('APP_URL') ?: '', '/');
require_once dirname(__DIR__, 2) . '/helpers/auth.php';

$badgeMap = [
    'completado' => 'success',
    'pendiente'  => 'warning',
    'fallido'    => 'danger',
    'cancelado'  => 'secondary',
];
$badgeColor = $badgeMap[$pago['estado']] ?? 'secondary';
?>
<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="app-wrapper">
    <?php require_once dirname(__DIR__) . '/layouts/sidebar.php'; ?>
    <div class="app-main">
        <?php require_once dirname(__DIR__) . '/layouts/navbar.php'; ?>
        <main class="main-content">

            <div class="page-header">
                <div>
                    <h1 class="page-title">Detalle de Pago</h1>
                    <p class="page-subtitle">
                        <code><?= htmlspecialchars($pago['referencia']) ?></code>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= $appUrl ?>/index.php?url=pagos" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver
                    </a>
                    <?php if (isAdmin()): ?>
                    <a href="<?= $appUrl ?>/index.php?url=pagos/delete/<?= $pago['id'] ?>"
                       class="btn btn-outline-danger"
                       onclick="return confirm('¿Eliminar este pago permanentemente?')"
                       id="deletePagoBtn">
                        <i class="bi bi-trash me-2"></i>Eliminar
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert" id="showPagoFlash">
                <i class="bi bi-check-circle me-2"></i><?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
            <?php endif; ?>

            <div class="row g-4">

                <!-- Detalle -->
                <div class="col-lg-8">
                    <div class="card-custom">
                        <div class="card-custom-header d-flex justify-content-between align-items-center">
                            <h2 class="card-custom-title mb-0">
                                <i class="bi bi-receipt me-2"></i>Comprobante de Pago
                            </h2>
                            <span class="badge bg-<?= $badgeColor ?> fs-6">
                                <?= ucfirst($pago['estado']) ?>
                            </span>
                        </div>
                        <div class="card-custom-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Referencia</dt>
                                <dd class="col-sm-8">
                                    <code><?= htmlspecialchars($pago['referencia']) ?></code>
                                </dd>
                                <dt class="col-sm-4">Servicio</dt>
                                <dd class="col-sm-8">
                                    <a href="<?= $appUrl ?>/index.php?url=servicios/show/<?= $pago['servicio_id'] ?>">
                                        <?= htmlspecialchars($pago['servicio_nombre']) ?>
                                    </a>
                                    <span class="badge bg-light text-dark border ms-1">
                                        <?= htmlspecialchars($pago['servicio_categoria']) ?>
                                    </span>
                                </dd>
                                <dt class="col-sm-4">Usuario</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($pago['usuario_nombre']) ?></dd>
                                <dt class="col-sm-4">Email</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($pago['usuario_email']) ?></dd>
                                <dt class="col-sm-4">Monto</dt>
                                <dd class="col-sm-8 fw-700 fs-5">
                                    $<?= number_format((float)$pago['monto'], 2) ?>
                                </dd>
                                <dt class="col-sm-4">Método</dt>
                                <dd class="col-sm-8"><?= ucfirst($pago['metodo_pago']) ?></dd>
                                <dt class="col-sm-4">Estado</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-<?= $badgeColor ?>">
                                        <?= ucfirst($pago['estado']) ?>
                                    </span>
                                </dd>
                                <dt class="col-sm-4">Notas</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($pago['notas'] ?? '—') ?></dd>
                                <dt class="col-sm-4">Fecha pago</dt>
                                <dd class="col-sm-8">
                                    <?= $pago['fecha_pago']
                                        ? date('d/m/Y H:i', strtotime($pago['fecha_pago']))
                                        : '—' ?>
                                </dd>
                                <dt class="col-sm-4">Registrado</dt>
                                <dd class="col-sm-8">
                                    <?= date('d/m/Y H:i', strtotime($pago['created_at'])) ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Panel de acciones (admin) -->
                <div class="col-lg-4">
                    <?php if (isAdmin()): ?>
                    <div class="card-custom">
                        <div class="card-custom-header">
                            <h2 class="card-custom-title"><i class="bi bi-sliders me-2"></i>Actualizar Estado</h2>
                        </div>
                        <div class="card-custom-body">
                            <form action="<?= $appUrl ?>/index.php?url=pagos/updateEstado/<?= $pago['id'] ?>"
                                  method="POST" id="updateEstadoForm">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Nuevo estado</label>
                                    <select class="form-select" id="estado" name="estado">
                                        <?php
                                        $estados = ['pendiente', 'completado', 'fallido', 'cancelado'];
                                        foreach ($estados as $e):
                                        ?>
                                        <option value="<?= $e ?>"
                                            <?= $pago['estado'] === $e ? 'selected' : '' ?>>
                                            <?= ucfirst($e) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100" id="updateEstadoSubmitBtn">
                                    <i class="bi bi-check-circle me-2"></i>Actualizar Estado
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Pago rápido del mismo servicio -->
                    <div class="card-custom mt-3">
                        <div class="card-custom-body text-center">
                            <p class="text-muted small mb-2">¿Pagar este servicio de nuevo?</p>
                            <a href="<?= $appUrl ?>/index.php?url=pagos/create&servicio_id=<?= $pago['servicio_id'] ?>"
                               class="btn btn-outline-primary w-100" id="repeatPaymentBtn">
                                <i class="bi bi-arrow-repeat me-2"></i>Repetir Pago
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
