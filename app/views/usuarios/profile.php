<?php
/**
 * Vista: Perfil del usuario actual
 */
$pageTitle = 'Mi Perfil';
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
                    <h1 class="page-title">Mi Perfil</h1>
                    <p class="page-subtitle">Gestiona tu información personal</p>
                </div>
            </div>

            <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert" id="profileFlash">
                <i class="bi bi-check-circle me-2"></i><?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
            <?php endif; ?>

            <div class="row g-4">

                <!-- Avatar y resumen -->
                <div class="col-lg-4">
                    <div class="card-custom text-center p-4">
                        <div class="user-avatar user-avatar--xl mx-auto mb-3">
                            <?= strtoupper(substr($usuario['nombre'], 0, 1)) ?>
                        </div>
                        <h2 class="h5 fw-700">
                            <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?>
                        </h2>
                        <p class="text-muted small mb-2"><?= htmlspecialchars($usuario['email']) ?></p>
                        <span class="badge bg-<?= $usuario['rol'] === 'admin' ? 'danger' : 'primary' ?> mb-3">
                            <?= $usuario['rol'] === 'admin' ? 'Administrador' : 'Usuario' ?>
                        </span>
                        <hr>
                        <dl class="row text-start mb-0">
                            <dt class="col-5 small text-muted">Teléfono</dt>
                            <dd class="col-7 small"><?= htmlspecialchars($usuario['telefono'] ?? '—') ?></dd>
                            <dt class="col-5 small text-muted">Miembro desde</dt>
                            <dd class="col-7 small"><?= date('M Y', strtotime($usuario['created_at'])) ?></dd>
                        </dl>
                    </div>
                </div>

                <!-- Formulario de edición -->
                <div class="col-lg-8">
                    <div class="card-custom">
                        <div class="card-custom-header">
                            <h2 class="card-custom-title"><i class="bi bi-pencil-square me-2"></i>Editar Perfil</h2>
                        </div>
                        <div class="card-custom-body">
                            <form action="<?= $appUrl ?>/index.php?url=usuarios/updateProfile"
                                  method="POST" id="profileForm" novalidate>

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required
                                               value="<?= htmlspecialchars($_POST['nombre'] ?? $usuario['nombre']) ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="apellido" name="apellido" required
                                               value="<?= htmlspecialchars($_POST['apellido'] ?? $usuario['apellido']) ?>">
                                    </div>

                                    <div class="col-12">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" required
                                               value="<?= htmlspecialchars($_POST['email'] ?? $usuario['email']) ?>">
                                    </div>

                                    <div class="col-12">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="tel" class="form-control" id="telefono" name="telefono"
                                               value="<?= htmlspecialchars($_POST['telefono'] ?? $usuario['telefono'] ?? '') ?>">
                                    </div>

                                </div>

                                <hr class="my-4">
                                <h3 class="h6 fw-600 mb-3">Cambiar contraseña <small class="text-muted fw-400">(dejar vacío para mantener)</small></h3>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Nueva contraseña</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password"
                                                   placeholder="Mínimo 8 caracteres" autocomplete="new-password">
                                            <button class="btn btn-outline-secondary" type="button"
                                                    id="togglePasswordBtn" aria-label="Ver contraseña">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password2" class="form-label">Confirmar contraseña</label>
                                        <input type="password" class="form-control" id="password2" name="password2"
                                               placeholder="Repite la contraseña" autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary" id="profileSubmitBtn">
                                        <i class="bi bi-save me-2"></i>Guardar Cambios
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
