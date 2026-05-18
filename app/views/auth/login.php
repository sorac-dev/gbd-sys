<?php
/**
 * Vista: Login
 */
$pageTitle = 'Iniciar Sesión';
require_once dirname(__DIR__, 2) . '/helpers/auth.php';
$appUrl = rtrim(getenv('APP_URL') ?: '', '/');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Inicia sesión en el sistema de pago de servicios GDB">
    <title>Iniciar Sesión | GDB Pagos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $appUrl ?>/css/style.css">
</head>
<body class="auth-layout">

<div class="auth-container">
    <div class="auth-card">

        <!-- Logo -->
        <div class="auth-logo">
            <div class="brand-icon brand-icon--lg">
                <i class="bi bi-credit-card-2-front-fill"></i>
            </div>
            <h1 class="auth-title">GDB Pagos</h1>
            <p class="auth-subtitle">Sistema de pago de servicios</p>
        </div>

        <!-- Flash -->
        <?php if ($flash): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert" id="authFlash">
            <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
            <?= $flash['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
        <?php endif; ?>

        <!-- Formulario -->
        <form action="<?= $appUrl ?>/index.php?url=auth/login" method="POST" id="loginForm" novalidate>

            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email"
                           placeholder="correo@ejemplo.com" required autocomplete="email">
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="••••••••" required autocomplete="current-password">
                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordBtn"
                            aria-label="Ver contraseña">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-lg" id="loginSubmitBtn">
                <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
            </button>

        </form>

        <hr class="my-3">
        <p class="text-center text-muted small mb-0">
            ¿No tienes cuenta?
            <a href="<?= $appUrl ?>/index.php?url=auth/register" class="link-primary fw-500" id="goToRegisterLink">
                Regístrate aquí
            </a>
        </p>

        <!-- Credenciales demo -->
        <div class="demo-box mt-3">
            <p class="small fw-600 mb-1"><i class="bi bi-info-circle me-1"></i>Cuentas de prueba</p>
            <div class="d-flex gap-3 flex-wrap">
                <div class="small">
                    <span class="badge bg-danger me-1">Admin</span>
                    <code>admin@gdb.com</code> / <code>password</code>
                </div>
                <div class="small">
                    <span class="badge bg-primary me-1">User</span>
                    <code>maria@gdb.com</code> / <code>password</code>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $appUrl ?>/js/app.js"></script>
</body>
</html>
