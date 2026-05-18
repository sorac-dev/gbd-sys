<?php
/**
 * Front Controller — public/index.php
 * Punto de entrada único de la aplicación MVC.
 */

declare(strict_types=1);

// ── Bootstrap ───────────────────────────────────────────────────────────────

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH',  BASE_PATH . '/app');

// Carga configuración y helpers globales
require_once APP_PATH . '/config/database.php';
require_once APP_PATH . '/helpers/auth.php';
require_once APP_PATH . '/helpers/redirect.php';

// Iniciar sesión
$sessionName     = getenv('SESSION_NAME') ?: 'gdb_session';
$sessionLifetime = (int) (getenv('SESSION_LIFETIME') ?: 7200);

session_name($sessionName);
session_set_cookie_params([
    'lifetime' => $sessionLifetime,
    'path'     => '/',
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

// ── Router ───────────────────────────────────────────────────────────────────

$url    = trim((string) ($_GET['url'] ?? 'dashboard'), '/');
$parts  = explode('/', $url);
$ctrl   = $parts[0] ?? 'dashboard';
$action = $parts[1] ?? 'index';
$param  = isset($parts[2]) ? (int) $parts[2] : null;

// Tabla de rutas: [controlador => [ [acción, método_HTTP] => [clase, método_clase, requiere_param] ]]
// Se usa "ACCION:METODO" como clave para evitar sobreescritura de claves duplicadas en PHP.
$routes = [

    // ── Auth ─────────────────────────────────────────────────────────────────
    'auth' => [
        'login:GET'       => ['AuthController', 'loginForm',    false],
        'login:POST'      => ['AuthController', 'login',        false],
        'register:GET'    => ['AuthController', 'registerForm', false],
        'register:POST'   => ['AuthController', 'register',     false],
        'logout:GET'      => ['AuthController', 'logout',       false],
    ],

    // ── Dashboard ─────────────────────────────────────────────────────────────
    'dashboard' => [
        'index:GET'  => ['DashboardController', 'index', false],
    ],

    // ── Servicios ─────────────────────────────────────────────────────────────
    'servicios' => [
        'index:GET'   => ['ServicioController', 'index',      false],
        'create:GET'  => ['ServicioController', 'createForm', false],
        'create:POST' => ['ServicioController', 'create',     false],
        'show:GET'    => ['ServicioController', 'show',       true],
        'edit:GET'    => ['ServicioController', 'editForm',   true],
        'edit:POST'   => ['ServicioController', 'edit',       true],
        'delete:GET'  => ['ServicioController', 'delete',     true],
    ],

    // ── Pagos ─────────────────────────────────────────────────────────────────
    'pagos' => [
        'index:GET'          => ['PagoController', 'index',       false],
        'create:GET'         => ['PagoController', 'createForm',  false],
        'create:POST'        => ['PagoController', 'create',      false],
        'show:GET'           => ['PagoController', 'show',        true],
        'delete:GET'         => ['PagoController', 'delete',      true],
        'updateEstado:POST'  => ['PagoController', 'updateEstado',true],
    ],

    // ── Usuarios ─────────────────────────────────────────────────────────────
    'usuarios' => [
        'index:GET'          => ['UsuarioController', 'index',        false],
        'edit:GET'           => ['UsuarioController', 'editForm',      true],
        'edit:POST'          => ['UsuarioController', 'edit',          true],
        'profile:GET'        => ['UsuarioController', 'profile',       false],
        'updateProfile:POST' => ['UsuarioController', 'updateProfile', false],
        'delete:GET'         => ['UsuarioController', 'delete',        true],
    ],
];

// ── Dispatch ─────────────────────────────────────────────────────────────────

$method          = $_SERVER['REQUEST_METHOD'];
$controllerClass = null;
$actionMethod    = null;
$needsParam      = false;
$matched         = false;

if (isset($routes[$ctrl])) {
    // Clave exacta: "accion:METODO_HTTP"
    $routeKey = $action . ':' . $method;

    if (isset($routes[$ctrl][$routeKey])) {
        [$controllerClass, $actionMethod, $needsParam] = $routes[$ctrl][$routeKey];
        $matched = true;
    }
}

// Si el controlador es "dashboard" sin acción explícita
if ($ctrl === 'dashboard' && $action === 'index') {
    $matched         = true;
    $controllerClass = 'DashboardController';
    $actionMethod    = 'index';
    $needsParam      = false;
}

if (!$matched || $controllerClass === null) {
    // 404 — redirigir al dashboard
    if (isLoggedIn()) {
        redirect('dashboard');
    } else {
        redirect('auth/login');
    }
}

// Cargar controlador
$controllerPath = APP_PATH . '/controllers/' . $controllerClass . '.php';

if (!file_exists($controllerPath)) {
    redirect(isLoggedIn() ? 'dashboard' : 'auth/login');
}

require_once $controllerPath;
$controller = new $controllerClass();

if (!method_exists($controller, $actionMethod)) {
    redirect(isLoggedIn() ? 'dashboard' : 'auth/login');
}

// Llamar el método con o sin parámetro
if ($needsParam && $param !== null) {
    $controller->$actionMethod($param);
} elseif ($needsParam && $param === null) {
    redirect(isLoggedIn() ? 'dashboard' : 'auth/login');
} else {
    $controller->$actionMethod();
}
