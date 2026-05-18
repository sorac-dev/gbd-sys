<?php
/**
 * AuthController — Registro, Login, Logout
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/models/Usuario.php';
require_once dirname(__DIR__) . '/helpers/auth.php';
require_once dirname(__DIR__) . '/helpers/redirect.php';

class AuthController
{
    private Usuario $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new Usuario();
    }

    // ── Login ───────────────────────────────────────────────────────────────

    public function loginForm(): void
    {
        if (isLoggedIn()) {
            redirect('dashboard');
        }
        $flash = getFlash();
        require_once dirname(__DIR__) . '/views/auth/login.php';
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('auth/login');
        }

        $email    = trim((string) ($_POST['email']    ?? ''));
        $password = trim((string) ($_POST['password'] ?? ''));

        if ($email === '' || $password === '') {
            setFlash('danger', 'Por favor completa todos los campos.');
            redirect('auth/login');
        }

        $usuario = $this->usuarioModel->findByEmail($email);

        if (!$usuario || !password_verify($password, $usuario['password'])) {
            setFlash('danger', 'Credenciales incorrectas. Intenta de nuevo.');
            redirect('auth/login');
        }

        if (!(bool) $usuario['activo']) {
            setFlash('warning', 'Tu cuenta está desactivada. Contacta al administrador.');
            redirect('auth/login');
        }

        loginUser($usuario);
        setFlash('success', '¡Bienvenido, ' . $usuario['nombre'] . '!');
        redirect('dashboard');
    }

    // ── Registro ────────────────────────────────────────────────────────────

    public function registerForm(): void
    {
        if (isLoggedIn()) {
            redirect('dashboard');
        }
        $flash = getFlash();
        require_once dirname(__DIR__) . '/views/auth/register.php';
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('auth/register');
        }

        $nombre    = trim((string) ($_POST['nombre']    ?? ''));
        $apellido  = trim((string) ($_POST['apellido']  ?? ''));
        $email     = trim((string) ($_POST['email']     ?? ''));
        $password  = trim((string) ($_POST['password']  ?? ''));
        $password2 = trim((string) ($_POST['password2'] ?? ''));
        $telefono  = trim((string) ($_POST['telefono']  ?? ''));

        // Validaciones
        $errors = [];
        if ($nombre   === '') $errors[] = 'El nombre es obligatorio.';
        if ($apellido === '') $errors[] = 'El apellido es obligatorio.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido.';
        if (strlen($password) < 8) $errors[] = 'La contraseña debe tener al menos 8 caracteres.';
        if ($password !== $password2) $errors[] = 'Las contraseñas no coinciden.';
        if ($this->usuarioModel->emailExists($email)) $errors[] = 'El email ya está registrado.';

        if (!empty($errors)) {
            setFlash('danger', implode('<br>', $errors));
            redirect('auth/register');
        }

        $id = $this->usuarioModel->create([
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'email'    => $email,
            'password' => $password,
            'telefono' => $telefono ?: null,
            'rol'      => 'user',
        ]);

        if ($id) {
            setFlash('success', 'Registro exitoso. Ahora puedes iniciar sesión.');
            redirect('auth/login');
        } else {
            setFlash('danger', 'Error al registrar. Intenta de nuevo.');
            redirect('auth/register');
        }
    }

    // ── Logout ──────────────────────────────────────────────────────────────

    public function logout(): void
    {
        logoutUser();
        redirect('auth/login');
    }
}
