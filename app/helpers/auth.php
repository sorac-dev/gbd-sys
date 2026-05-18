<?php
/**
 * Helpers de autenticación
 */

declare(strict_types=1);

/** Comprueba si hay sesión activa. */
function isLoggedIn(): bool
{
    return isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id']);
}

/** Requiere sesión activa; redirige al login si no la hay. */
function requireAuth(): void
{
    if (!isLoggedIn()) {
        redirect('auth/login');
    }
}

/** Requiere rol de administrador. */
function requireAdmin(): void
{
    requireAuth();
    if (($_SESSION['usuario_rol'] ?? '') !== 'admin') {
        redirect('dashboard');
    }
}

/** Devuelve el id del usuario en sesión. */
function currentUserId(): int
{
    return (int) ($_SESSION['usuario_id'] ?? 0);
}

/** Devuelve el nombre del usuario en sesión. */
function currentUserName(): string
{
    return (string) ($_SESSION['usuario_nombre'] ?? 'Invitado');
}

/** Devuelve el rol del usuario en sesión. */
function currentUserRole(): string
{
    return (string) ($_SESSION['usuario_rol'] ?? 'user');
}

/** Comprueba si el usuario actual es admin. */
function isAdmin(): bool
{
    return currentUserRole() === 'admin';
}

/** Inicia sesión para el usuario dado. */
function loginUser(array $usuario): void
{
    session_regenerate_id(true);
    $_SESSION['usuario_id']     = $usuario['id'];
    $_SESSION['usuario_nombre'] = $usuario['nombre'] . ' ' . $usuario['apellido'];
    $_SESSION['usuario_email']  = $usuario['email'];
    $_SESSION['usuario_rol']    = $usuario['rol'];
}

/** Destruye la sesión activa. */
function logoutUser(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    session_destroy();
}

/** Guarda un mensaje flash en sesión. */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/** Obtiene y elimina el mensaje flash de la sesión. */
function getFlash(): ?array
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
