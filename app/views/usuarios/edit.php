<?php
/**
 * Vista: Editar usuario (admin)
 */
$pageTitle = 'Editar Usuario';
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
                    <h1 class="page-title">Editar Usuario</h1>
                    <p class="page-subtitle">
                        <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?>
                    </p>
                </div>
                <a href="<?= $appUrl ?>/index.php?url=usuarios" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
            </div>

            <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert" id="editUsuarioFlash">
                <?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="card-custom">
                        <div class="card-custom-body">
                            <form action="<?= $appUrl ?>/index.php?url=usuarios/edit/<?= $usuario['id'] ?>"
                                  method="POST" id="editUsuarioForm" novalidate>

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

                                    <div class="col-md-6">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="tel" class="form-control" id="telefono" name="telefono"
                                               value="<?= htmlspecialchars($_POST['telefono'] ?? $usuario['telefono'] ?? '') ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="rol" class="form-label">Rol</label>
                                        <select class="form-select" id="rol" name="rol">
                                            <option value="user"
                                                <?= (($_POST['rol'] ?? $usuario['rol']) === 'user') ? 'selected' : '' ?>>
                                                Usuario
                                            </option>
                                            <option value="admin"
                                                <?= (($_POST['rol'] ?? $usuario['rol']) === 'admin') ? 'selected' : '' ?>>
                                                Administrador
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password" class="form-label">
                                            Nueva contraseña
                                            <small class="text-muted">(dejar vacío para mantener)</small>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password"
                                                   placeholder="••••••••" autocomplete="new-password">
                                            <button class="btn btn-outline-secondary" type="button"
                                                    id="togglePasswordBtn" aria-label="Ver contraseña">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-6 d-flex align-items-end">
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" id="activo" name="activo"
                                                   <?= (bool)($_POST['activo'] ?? $usuario['activo']) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="activo">Cuenta activa</label>
                                        </div>
                                    </div>

                                </div>

                                <div class="d-flex gap-2 justify-content-end mt-4">
                                    <a href="<?= $appUrl ?>/index.php?url=usuarios" class="btn btn-outline-secondary">
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="editUsuarioSubmitBtn">
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
