<?php
/**
 * UsuarioController — Gestión de usuarios
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/models/Usuario.php';
require_once dirname(__DIR__) . '/helpers/auth.php';
require_once dirname(__DIR__) . '/helpers/redirect.php';

class UsuarioController
{
    private Usuario $model;

    public function __construct()
    {
        $this->model = new Usuario();
    }

    /** Listado de usuarios (admin). */
    public function index(): void
    {
        requireAdmin();
        $flash    = getFlash();
        $usuarios = $this->model->all();
        require_once dirname(__DIR__) . '/views/usuarios/index.php';
    }

    /** Formulario de edición de usuario (admin). */
    public function editForm(int $id): void
    {
        requireAdmin();
        $usuario = $this->model->findById($id);
        if (!$usuario) {
            setFlash('warning', 'Usuario no encontrado.');
            redirect('usuarios');
        }
        $flash = getFlash();
        require_once dirname(__DIR__) . '/views/usuarios/edit.php';
    }

    /** Procesa edición de usuario (admin). */
    public function edit(int $id): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('usuarios/edit/' . $id);
        }

        $nombre   = trim((string) ($_POST['nombre']   ?? ''));
        $apellido = trim((string) ($_POST['apellido'] ?? ''));
        $email    = trim((string) ($_POST['email']    ?? ''));
        $telefono = trim((string) ($_POST['telefono'] ?? ''));
        $rol      = trim((string) ($_POST['rol']      ?? 'user'));
        $activo   = isset($_POST['activo']) ? 1 : 0;
        $password = trim((string) ($_POST['password'] ?? ''));

        $errors = [];
        if ($nombre   === '') $errors[] = 'El nombre es obligatorio.';
        if ($apellido === '') $errors[] = 'El apellido es obligatorio.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido.';
        if ($password !== '' && strlen($password) < 8) {
            $errors[] = 'La contraseña debe tener al menos 8 caracteres.';
        }

        if (!empty($errors)) {
            setFlash('danger', implode('<br>', $errors));
            redirect('usuarios/edit/' . $id);
        }

        $data = [
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'email'    => $email,
            'telefono' => $telefono ?: null,
            'rol'      => in_array($rol, ['admin', 'user']) ? $rol : 'user',
            'activo'   => $activo,
        ];
        if ($password !== '') {
            $data['password'] = $password;
        }

        $this->model->update($id, $data);
        setFlash('success', 'Usuario actualizado correctamente.');
        redirect('usuarios');
    }

    /** Perfil del usuario actual. */
    public function profile(): void
    {
        requireAuth();
        $flash   = getFlash();
        $usuario = $this->model->findById(currentUserId());
        require_once dirname(__DIR__) . '/views/usuarios/profile.php';
    }

    /** Actualiza el perfil propio. */
    public function updateProfile(): void
    {
        requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('usuarios/profile');
        }

        $id       = currentUserId();
        $nombre   = trim((string) ($_POST['nombre']   ?? ''));
        $apellido = trim((string) ($_POST['apellido'] ?? ''));
        $email    = trim((string) ($_POST['email']    ?? ''));
        $telefono = trim((string) ($_POST['telefono'] ?? ''));
        $password = trim((string) ($_POST['password'] ?? ''));
        $password2= trim((string) ($_POST['password2'] ?? ''));

        $errors = [];
        if ($nombre   === '') $errors[] = 'El nombre es obligatorio.';
        if ($apellido === '') $errors[] = 'El apellido es obligatorio.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido.';
        if ($password !== '') {
            if (strlen($password) < 8) $errors[] = 'La contraseña debe tener al menos 8 caracteres.';
            if ($password !== $password2) $errors[] = 'Las contraseñas no coinciden.';
        }

        if (!empty($errors)) {
            setFlash('danger', implode('<br>', $errors));
            redirect('usuarios/profile');
        }

        $data = [
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'email'    => $email,
            'telefono' => $telefono ?: null,
        ];
        if ($password !== '') {
            $data['password'] = $password;
        }

        $this->model->update($id, $data);

        // Actualizar nombre en sesión
        $_SESSION['usuario_nombre'] = $nombre . ' ' . $apellido;
        $_SESSION['usuario_email']  = $email;

        setFlash('success', 'Perfil actualizado correctamente.');
        redirect('usuarios/profile');
    }

    /** Elimina un usuario (admin). */
    public function delete(int $id): void
    {
        requireAdmin();
        if ($id === currentUserId()) {
            setFlash('danger', 'No puedes eliminar tu propia cuenta.');
            redirect('usuarios');
        }
        $this->model->delete($id);
        setFlash('success', 'Usuario eliminado.');
        redirect('usuarios');
    }
}
