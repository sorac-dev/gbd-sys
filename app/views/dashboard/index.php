<?php
/**
 * Vista: Dashboard
 */
$pageTitle = 'Dashboard';
$appUrl    = rtrim(getenv('APP_URL') ?: '', '/');

require_once dirname(__DIR__, 2) . '/helpers/auth.php';

$badgeMap = [
    'completado' => 'success',
    'pendiente'  => 'warning',
    'fallido'    => 'danger',
    'cancelado'  => 'secondary',
];
?>
<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="app-wrapper">
    <?php require_once dirname(__DIR__) . '/layouts/sidebar.php'; ?>

    <div class="app-main">
        <?php require_once dirname(__DIR__) . '/layouts/navbar.php'; ?>

        <main class="main-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Dashboard</h1>
                    <p class="page-subtitle">
                        Bienvenido, <strong><?= htmlspecialchars(currentUserName()) ?></strong>
                    </p>
                </div>
                <a href="<?= $appUrl ?>/index.php?url=pagos/create" class="btn btn-primary" id="dashboardNewPaymentBtn">
                    <i class="bi bi-plus-circle me-2"></i>Nuevo Pago
                </a>
            </div>

            <!-- Flash -->
            <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert" id="dashboardFlash">
                <i class="bi bi-check-circle me-2"></i><?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
            <?php endif; ?>

            <?php if (isAdmin()): ?>
            <!-- ── KPIs Admin ─────────────────────────────────────────────── -->
            <div class="row g-4 mb-4" id="adminKpis">
                <div class="col-sm-6 col-xl-3">
                    <div class="kpi-card kpi-card--primary">
                        <div class="kpi-icon"><i class="bi bi-people-fill"></i></div>
                        <div class="kpi-body">
                            <div class="kpi-value"><?= number_format($totalUsuarios) ?></div>
                            <div class="kpi-label">Usuarios Registrados</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="kpi-card kpi-card--info">
                        <div class="kpi-icon"><i class="bi bi-grid-3x3-gap-fill"></i></div>
                        <div class="kpi-body">
                            <div class="kpi-value"><?= number_format($totalServicios) ?></div>
                            <div class="kpi-label">Servicios Activos</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="kpi-card kpi-card--warning">
                        <div class="kpi-icon"><i class="bi bi-receipt"></i></div>
                        <div class="kpi-body">
                            <div class="kpi-value"><?= number_format($totalPagos) ?></div>
                            <div class="kpi-label">Total de Pagos</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="kpi-card kpi-card--success">
                        <div class="kpi-icon"><i class="bi bi-currency-dollar"></i></div>
                        <div class="kpi-body">
                            <div class="kpi-value">$<?= number_format($totalRecaudado, 2) ?></div>
                            <div class="kpi-label">Total Recaudado</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas por estado -->
            <?php if (!empty($estadisticas)): ?>
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="card-custom">
                        <div class="card-custom-header">
                            <h2 class="card-custom-title"><i class="bi bi-bar-chart me-2"></i>Pagos por Estado</h2>
                        </div>
                        <div class="card-custom-body">
                            <div class="row g-3">
                                <?php foreach ($estadisticas as $est): ?>
                                <div class="col-sm-6 col-md-3">
                                    <div class="estado-stat bg-<?= $badgeMap[$est['estado']] ?? 'secondary' ?>-subtle
                                                border border-<?= $badgeMap[$est['estado']] ?? 'secondary' ?>-subtle rounded-3 p-3 text-center">
                                        <div class="fs-3 fw-700"><?= $est['total'] ?></div>
                                        <div class="small text-capitalize"><?= $est['estado'] ?></div>
                                        <div class="small text-muted">$<?= number_format((float)$est['monto'], 2) ?></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php else: ?>
            <!-- ── KPIs Usuario Regular ────────────────────────────────────── -->
            <div class="row g-4 mb-4" id="userKpis">
                <div class="col-sm-6">
                    <div class="kpi-card kpi-card--info">
                        <div class="kpi-icon"><i class="bi bi-grid-3x3-gap-fill"></i></div>
                        <div class="kpi-body">
                            <div class="kpi-value"><?= number_format($totalServicios) ?></div>
                            <div class="kpi-label">Servicios Disponibles</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="kpi-card kpi-card--primary">
                        <div class="kpi-icon"><i class="bi bi-receipt"></i></div>
                        <div class="kpi-body">
                            <div class="kpi-value"><?= count($ultimosPagos) ?></div>
                            <div class="kpi-label">Mis Últimos Pagos</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Tabla de últimos pagos -->
            <div class="card-custom">
                <div class="card-custom-header d-flex justify-content-between align-items-center">
                    <h2 class="card-custom-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        <?= isAdmin() ? 'Últimos Pagos del Sistema' : 'Mis Últimos Pagos' ?>
                    </h2>
                    <a href="<?= $appUrl ?>/index.php?url=pagos" class="btn btn-sm btn-outline-primary" id="viewAllPaymentsBtn">
                        Ver todos <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-custom-body p-0">
                    <?php if (empty($ultimosPagos)): ?>
                    <div class="empty-state">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-2">Aún no hay pagos registrados.</p>
                        <a href="<?= $appUrl ?>/index.php?url=pagos/create" class="btn btn-primary" id="emptyStatePayBtn">
                            <i class="bi bi-plus-circle me-2"></i>Realizar Primer Pago
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dashboardPaymentsTable">
                            <thead>
                                <tr>
                                    <th>Referencia</th>
                                    <?php if (isAdmin()): ?><th>Usuario</th><?php endif; ?>
                                    <th>Servicio</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ultimosPagos as $pago): ?>
                                <tr>
                                    <td><code class="small"><?= htmlspecialchars($pago['referencia']) ?></code></td>
                                    <?php if (isAdmin()): ?>
                                    <td><?= htmlspecialchars($pago['usuario_nombre']) ?></td>
                                    <?php endif; ?>
                                    <td><?= htmlspecialchars($pago['servicio_nombre']) ?></td>
                                    <td class="fw-600">$<?= number_format((float)$pago['monto'], 2) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $badgeMap[$pago['estado']] ?? 'secondary' ?>">
                                            <?= ucfirst($pago['estado']) ?>
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        <?= date('d/m/Y', strtotime($pago['created_at'])) ?>
                                    </td>
                                    <td>
                                        <a href="<?= $appUrl ?>/index.php?url=pagos/show/<?= $pago['id'] ?>"
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </main>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
