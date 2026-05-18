<?php
/**
 * Vista: Historial de pagos
 */
$pageTitle = 'Historial de Pagos';
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
                    <h1 class="page-title"><?= isAdmin() ? 'Todos los Pagos' : 'Mis Pagos' ?></h1>
                    <p class="page-subtitle">Historial completo de transacciones</p>
                </div>
                <a href="<?= $appUrl ?>/index.php?url=pagos/create" class="btn btn-primary" id="newPaymentFromListBtn">
                    <i class="bi bi-plus-circle me-2"></i>Nuevo Pago
                </a>
            </div>

            <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert" id="pagosFlash">
                <i class="bi bi-check-circle me-2"></i><?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
            <?php endif; ?>

            <!-- Filtros -->
            <div class="card-custom mb-4">
                <div class="card-custom-body">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <input type="text" id="searchPagos" class="form-control"
                                   placeholder="Buscar por referencia, servicio…">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterEstado">
                                <option value="">Todos los estados</option>
                                <option value="completado">Completado</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="fallido">Fallido</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-secondary w-100" id="clearFiltersBtn">
                                <i class="bi bi-x-circle me-1"></i>Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="card-custom">
                <div class="card-custom-body p-0">
                    <?php if (empty($pagos)): ?>
                    <div class="empty-state">
                        <i class="bi bi-receipt fs-1 text-muted"></i>
                        <p class="text-muted mt-2">No hay pagos registrados.</p>
                        <a href="<?= $appUrl ?>/index.php?url=pagos/create" class="btn btn-primary" id="emptyPagosBtn">
                            <i class="bi bi-plus-circle me-2"></i>Realizar Primer Pago
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="pagosTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Referencia</th>
                                    <?php if (isAdmin()): ?><th>Usuario</th><?php endif; ?>
                                    <th>Servicio</th>
                                    <th>Monto</th>
                                    <th>Método</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="pagosTableBody">
                                <?php foreach ($pagos as $p): ?>
                                <tr class="pago-row"
                                    data-search="<?= strtolower(htmlspecialchars($p['referencia'] . ' ' . $p['servicio_nombre'] . ' ' . ($p['usuario_nombre'] ?? ''))) ?>"
                                    data-estado="<?= $p['estado'] ?>">
                                    <td class="text-muted small"><?= $p['id'] ?></td>
                                    <td><code class="small"><?= htmlspecialchars($p['referencia']) ?></code></td>
                                    <?php if (isAdmin()): ?>
                                    <td><?= htmlspecialchars($p['usuario_nombre']) ?></td>
                                    <?php endif; ?>
                                    <td><?= htmlspecialchars($p['servicio_nombre']) ?></td>
                                    <td class="fw-600">$<?= number_format((float)$p['monto'], 2) ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?= ucfirst($p['metodo_pago']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $badgeMap[$p['estado']] ?? 'secondary' ?>">
                                            <?= ucfirst($p['estado']) ?>
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        <?= date('d/m/Y', strtotime($p['created_at'])) ?>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="<?= $appUrl ?>/index.php?url=pagos/show/<?= $p['id'] ?>"
                                               class="btn btn-sm btn-outline-primary" title="Ver detalle">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <?php if (isAdmin()): ?>
                                            <a href="<?= $appUrl ?>/index.php?url=pagos/delete/<?= $p['id'] ?>"
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('¿Eliminar este pago?')"
                                               title="Eliminar"
                                               id="deletePago<?= $p['id'] ?>">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 border-top text-muted small" id="pagosCount">
                        <?= count($pagos) ?> pago(s) encontrado(s)
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </main>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
