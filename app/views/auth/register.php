<?php
/**
 * Vista: Registro
 */
$appUrl = rtrim(getenv('APP_URL') ?: '', '/');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Crea tu cuenta en el sistema de pago de servicios GDB">
    <title>Registro | GDB Pagos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $appUrl ?>/css/style.css">
</head>
<body class="auth-layout">

<div class="auth-container">
    <div class="auth-card auth-card--wide">

        <div class="auth-logo">
            <div class="brand-icon brand-icon--lg">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <h1 class="auth-title">Crear Cuenta</h1>
            <p class="auth-subtitle">Únete al sistema GDB Pagos</p>
        </div>

        <?php if ($flash): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert" id="registerFlash">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <?= $flash['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
        <?php endif; ?>

        <form action="<?= $appUrl ?>/index.php?url=auth/register" method="POST" id="registerForm" novalidate>

            <div class="row g-3">

                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                               placeholder="Juan" required
                               value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="apellido" name="apellido"
                               placeholder="Pérez" required
                               value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-12">
                    <label for="email" class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="correo@ejemplo.com" required autocomplete="email"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-12">
                    <label for="telefono" class="form-label">Teléfono <small class="text-muted">(opcional)</small></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-phone"></i></span>
                        <input type="tel" class="form-control" id="telefono" name="telefono"
                               placeholder="555-0000"
                               value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Mínimo 8 caracteres" required autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePasswordBtn" aria-label="Ver contraseña">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="password2" class="form-label">Confirmar contraseña <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="password2" name="password2"
                               placeholder="Repite la contraseña" required autocomplete="new-password">
                    </div>
                </div>

            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-100 btn-lg" id="registerSubmitBtn">
                    <i class="bi bi-person-check me-2"></i>Crear Cuenta
                </button>
            </div>

        </form>

        <hr class="my-3">
        <p class="text-center text-muted small mb-0">
            ¿Ya tienes cuenta?
            <a href="<?= $appUrl ?>/index.php?url=auth/login" class="link-primary fw-500" id="goToLoginLink">
                Inicia sesión aquí
            </a>
        </p>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $appUrl ?>/js/app.js"></script>
</body>
</html>
