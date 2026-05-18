<?php
/**
 * Vista: Crear nuevo pago
 */
$pageTitle = 'Nuevo Pago';
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
                    <h1 class="page-title">Nuevo Pago</h1>
                    <p class="page-subtitle">Selecciona un servicio y completa el pago</p>
                </div>
                <a href="<?= $appUrl ?>/index.php?url=pagos" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
            </div>

            <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert" id="createPagoFlash">
                <?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
            <?php endif; ?>

            <div class="row g-4">

                <!-- Formulario -->
                <div class="col-lg-7">
                    <div class="card-custom">
                        <div class="card-custom-header">
                            <h2 class="card-custom-title"><i class="bi bi-credit-card me-2"></i>Datos del Pago</h2>
                        </div>
                        <div class="card-custom-body">
                            <form action="<?= $appUrl ?>/index.php?url=pagos/create" method="POST"
                                  id="createPagoForm" novalidate>

                                <!-- Admin: selector de usuario -->
                                <?php if (isAdmin() && !empty($usuarios)): ?>
                                <div class="mb-3">
                                    <label for="usuario_id" class="form-label">Usuario <span class="text-danger">*</span></label>
                                    <select class="form-select" id="usuario_id" name="usuario_id" required>
                                        <option value="">Selecciona un usuario…</option>
                                        <?php foreach ($usuarios as $u): ?>
                                        <option value="<?= $u['id'] ?>"
                                            <?= ((int)($_POST['usuario_id'] ?? 0) === (int)$u['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($u['nombre'] . ' ' . $u['apellido'] . ' (' . $u['email'] . ')') ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php endif; ?>

                                <!-- Servicio -->
                                <div class="mb-3">
                                    <label for="servicio_id" class="form-label">Servicio <span class="text-danger">*</span></label>
                                    <select class="form-select" id="servicio_id" name="servicio_id" required>
                                        <option value="">Selecciona un servicio…</option>
                                        <?php foreach ($servicios as $s): ?>
                                        <option value="<?= $s['id'] ?>"
                                                data-precio="<?= $s['precio'] ?>"
                                                data-desc="<?= htmlspecialchars($s['descripcion'] ?? '') ?>"
                                                data-cat="<?= htmlspecialchars($s['categoria']) ?>"
                                            <?= ((int)($_POST['servicio_id'] ?? $servicioSeleccionado['id'] ?? 0) === (int)$s['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($s['nombre']) ?>
                                            — $<?= number_format((float)$s['precio'], 2) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Precio (readonly) -->
                                <div class="mb-3">
                                    <label for="monto_display" class="form-label">Monto a pagar</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="text" class="form-control fw-600" id="monto_display"
                                               placeholder="Selecciona un servicio" readonly>
                                    </div>
                                </div>

                                <!-- Método de pago -->
                                <div class="mb-3">
                                    <label class="form-label">Método de pago <span class="text-danger">*</span></label>
                                    <div class="payment-methods-grid" id="paymentMethodsGrid">
                                        <?php
                                        $metodos = [
                                            'efectivo'      => ['Efectivo',      'cash-stack'],
                                            'tarjeta'       => ['Tarjeta',       'credit-card'],
                                            'transferencia' => ['Transferencia', 'bank'],
                                        ];
                                        $selMetodo = $_POST['metodo_pago'] ?? 'tarjeta';
                                        foreach ($metodos as $val => [$label, $icon]):
                                        ?>
                                        <label class="payment-method-option <?= $selMetodo === $val ? 'selected' : '' ?>"
                                               for="metodo_<?= $val ?>">
                                            <input type="radio" name="metodo_pago" id="metodo_<?= $val ?>"
                                                   value="<?= $val ?>" class="d-none"
                                                   <?= $selMetodo === $val ? 'checked' : '' ?>>
                                            <i class="bi bi-<?= $icon ?> fs-3"></i>
                                            <span><?= $label ?></span>
                                        </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- Admin: estado inicial -->
                                <?php if (isAdmin()): ?>
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado inicial</label>
                                    <select class="form-select" id="estado" name="estado">
                                        <option value="pendiente">Pendiente</option>
                                        <option value="completado">Completado</option>
                                        <option value="fallido">Fallido</option>
                                        <option value="cancelado">Cancelado</option>
                                    </select>
                                </div>
                                <?php endif; ?>

                                <!-- Notas -->
                                <div class="mb-4">
                                    <label for="notas" class="form-label">Notas <small class="text-muted">(opcional)</small></label>
                                    <textarea class="form-control" id="notas" name="notas" rows="2"
                                              placeholder="Observaciones adicionales…"><?= htmlspecialchars($_POST['notas'] ?? '') ?></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100" id="createPagoSubmitBtn">
                                    <i class="bi bi-check-circle me-2"></i>Confirmar Pago
                                </button>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- Resumen -->
                <div class="col-lg-5">
                    <div class="card-custom sticky-top" style="top:1rem;" id="paymentSummaryCard">
                        <div class="card-custom-header">
                            <h2 class="card-custom-title"><i class="bi bi-receipt me-2"></i>Resumen del Pago</h2>
                        </div>
                        <div class="card-custom-body" id="paymentSummaryBody">
                            <div class="empty-state py-4">
                                <i class="bi bi-grid text-muted fs-2"></i>
                                <p class="text-muted mt-2 small">Selecciona un servicio para ver el resumen</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
