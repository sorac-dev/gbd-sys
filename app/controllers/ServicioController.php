<?php
/**
 * ServicioController — CRUD de servicios (solo admin)
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/models/Servicio.php';
require_once dirname(__DIR__) . '/helpers/auth.php';
require_once dirname(__DIR__) . '/helpers/redirect.php';

class ServicioController
{
    private Servicio $model;

    public function __construct()
    {
        $this->model = new Servicio();
    }

    /** Listado de todos los servicios. */
    public function index(): void
    {
        requireAuth();
        $flash     = getFlash();
        $servicios = $this->model->all();
        require_once dirname(__DIR__) . '/views/servicios/index.php';
    }

    /** Detalle de un servicio. */
    public function show(int $id): void
    {
        requireAuth();
        $servicio = $this->model->findById($id);
        if (!$servicio) {
            setFlash('warning', 'Servicio no encontrado.');
            redirect('servicios');
        }
        $flash = getFlash();
        require_once dirname(__DIR__) . '/views/servicios/show.php';
    }

    /** Formulario de creación. */
    public function createForm(): void
    {
        requireAdmin();
        $flash      = getFlash();
        $categorias = $this->model->categorias();
        require_once dirname(__DIR__) . '/views/servicios/create.php';
    }

    /** Procesa la creación. */
    public function create(): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('servicios/create');
        }

        $nombre      = trim((string) ($_POST['nombre']      ?? ''));
        $descripcion = trim((string) ($_POST['descripcion'] ?? ''));
        $categoria   = trim((string) ($_POST['categoria']   ?? ''));
        $precio      = (float) ($_POST['precio'] ?? 0);
        $activo      = isset($_POST['activo']) ? 1 : 0;

        $errors = [];
        if ($nombre    === '') $errors[] = 'El nombre es obligatorio.';
        if ($categoria === '') $errors[] = 'La categoría es obligatoria.';
        if ($precio    <= 0)   $errors[] = 'El precio debe ser mayor a 0.';

        if (!empty($errors)) {
            setFlash('danger', implode('<br>', $errors));
            redirect('servicios/create');
        }

        $id = $this->model->create([
            'nombre'      => $nombre,
            'descripcion' => $descripcion ?: null,
            'categoria'   => $categoria,
            'precio'      => $precio,
            'activo'      => $activo,
        ]);

        setFlash('success', 'Servicio creado exitosamente.');
        redirect('servicios/show/' . $id);
    }

    /** Formulario de edición. */
    public function editForm(int $id): void
    {
        requireAdmin();
        $servicio = $this->model->findById($id);
        if (!$servicio) {
            setFlash('warning', 'Servicio no encontrado.');
            redirect('servicios');
        }
        $flash      = getFlash();
        $categorias = $this->model->categorias();
        require_once dirname(__DIR__) . '/views/servicios/edit.php';
    }

    /** Procesa la edición. */
    public function edit(int $id): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('servicios/edit/' . $id);
        }

        $servicio = $this->model->findById($id);
        if (!$servicio) {
            setFlash('warning', 'Servicio no encontrado.');
            redirect('servicios');
        }

        $nombre      = trim((string) ($_POST['nombre']      ?? ''));
        $descripcion = trim((string) ($_POST['descripcion'] ?? ''));
        $categoria   = trim((string) ($_POST['categoria']   ?? ''));
        $precio      = (float) ($_POST['precio'] ?? 0);
        $activo      = isset($_POST['activo']) ? 1 : 0;

        $errors = [];
        if ($nombre    === '') $errors[] = 'El nombre es obligatorio.';
        if ($categoria === '') $errors[] = 'La categoría es obligatoria.';
        if ($precio    <= 0)   $errors[] = 'El precio debe ser mayor a 0.';

        if (!empty($errors)) {
            setFlash('danger', implode('<br>', $errors));
            redirect('servicios/edit/' . $id);
        }

        $this->model->update($id, [
            'nombre'      => $nombre,
            'descripcion' => $descripcion ?: null,
            'categoria'   => $categoria,
            'precio'      => $precio,
            'activo'      => $activo,
        ]);

        setFlash('success', 'Servicio actualizado correctamente.');
        redirect('servicios/show/' . $id);
    }

    /** Elimina un servicio. */
    public function delete(int $id): void
    {
        requireAdmin();
        $servicio = $this->model->findById($id);
        if (!$servicio) {
            setFlash('warning', 'Servicio no encontrado.');
            redirect('servicios');
        }
        $this->model->delete($id);
        setFlash('success', 'Servicio eliminado.');
        redirect('servicios');
    }
}
