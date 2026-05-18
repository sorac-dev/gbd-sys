<?php
/**
 * Helpers de redirección
 */

declare(strict_types=1);

/**
 * Redirige a una ruta interna de la aplicación.
 * Ejemplo: redirect('auth/login') → /gdb/public/index.php?url=auth/login
 */
function redirect(string $path): never
{
    $base = rtrim(getenv('APP_URL') ?: '', '/');
    header("Location: $base/index.php?url=" . ltrim($path, '/'));
    exit;
}

/**
 * Redirige a una URL absoluta externa.
 */
function redirectTo(string $url): never
{
    header("Location: $url");
    exit;
}

/**
 * Retrocede a la página anterior.
 */
function redirectBack(): never
{
    $referer = $_SERVER['HTTP_REFERER'] ?? null;
    if ($referer) {
        redirectTo($referer);
    }
    redirect('dashboard');
}
