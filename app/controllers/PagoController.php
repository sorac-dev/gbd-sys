<?php
/**
 * PagoController — Gestión de pagos
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/models/Pago.php';
require_once dirname(__DIR__) . '/models/Servicio.php';
require_once dirname(__DIR__) . '/models/Usuario.php';
require_once dirname(__DIR__) . '/helpers/auth.php';
require_once dirname(__DIR__) . '/helpers/redirect.php';

class PagoController
{
    private Pago     $pagoModel;
    private Servicio $servicioModel;
    private Usuario  $usuarioModel;

    public function __construct()
    {
        $this->pagoModel     = new Pago();
        $this->servicioModel = new Servicio();
        $this->usuarioModel  = new Usuario();
    }

    /** Historial de pagos. */
    public function index(): void
    {
        requireAuth();
        $flash = getFlash();
        if (isAdmin()) {
            $pagos = $this->pagoModel->all();
        } else {
            $pagos = $this->pagoModel->byUsuario(currentUserId());
        }
        require_once dirname(__DIR__) . '/views/pagos/index.php';
    }

    /** Detalle de un pago. */
    public function show(int $id): void
    {
        requireAuth();
        $pago = $this->pagoModel->findById($id);
        if (!$pago) {
            setFlash('warning', 'Pago no encontrado.');
            redirect('pagos');
        }
        // Usuario solo puede ver sus propios pagos
        if (!isAdmin() && (int) $pago['usuario_id'] !== currentUserId()) {
            setFlash('danger', 'No tienes permiso para ver ese pago.');
            redirect('pagos');
        }
        $flash = getFlash();
        require_once dirname(__DIR__) . '/views/pagos/show.php';
    }

    /** Formulario de nuevo pago. */
    public function createForm(): void
    {
        requireAuth();
        $flash     = getFlash();
        $servicios = $this->servicioModel->all(true);
        $usuarios  = isAdmin() ? $this->usuarioModel->all() : [];
        // Si viene un servicio preseleccionado
        $servicioSeleccionado = null;
        if (!empty($_GET['servicio_id'])) {
            $servicioSeleccionado = $this->servicioModel->findById((int) $_GET['servicio_id']);
        }
        require_once dirname(__DIR__) . '/views/pagos/create.php';
    }

    /** Procesa el pago. */
    public function create(): void
    {
        requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('pagos/create');
        }

        $servicioId = (int) ($_POST['servicio_id'] ?? 0);
        $metodoPago = trim((string) ($_POST['metodo_pago'] ?? ''));
        $notas      = trim((string) ($_POST['notas']       ?? ''));
        $estado     = trim((string) ($_POST['estado']      ?? 'pendiente'));

        // El admin puede asignar a cualquier usuario
        if (isAdmin() && !empty($_POST['usuario_id'])) {
            $usuarioId = (int) $_POST['usuario_id'];
        } else {
            $usuarioId = currentUserId();
        }

        $errors = [];
        if ($servicioId <= 0) $errors[] = 'Selecciona un servicio válido.';
        if (!in_array($metodoPago, ['efectivo', 'tarjeta', 'transferencia'])) {
            $errors[] = 'Método de pago inválido.';
        }

        $servicio = $this->servicioModel->findById($servicioId);
        if (!$servicio || !(bool) $servicio['activo']) {
            $errors[] = 'El servicio seleccionado no está disponible.';
        }

        if (!empty($errors)) {
            setFlash('danger', implode('<br>', $errors));
            redirect('pagos/create');
        }

        $id = $this->pagoModel->create([
            'usuario_id'  => $usuarioId,
            'servicio_id' => $servicioId,
            'monto'       => $servicio['precio'],
            'metodo_pago' => $metodoPago,
            'notas'       => $notas ?: null,
            'estado'      => isAdmin() ? $estado : 'pendiente',
        ]);

        setFlash('success', 'Pago registrado con referencia: ' . $this->pagoModel->findById($id)['referencia']);
        redirect('pagos/show/' . $id);
    }

    /** Actualiza el estado de un pago (solo admin). */
    public function updateEstado(int $id): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('pagos');
        }
        $estado = trim((string) ($_POST['estado'] ?? ''));
        $validos = ['pendiente', 'completado', 'fallido', 'cancelado'];
        if (!in_array($estado, $validos)) {
            setFlash('danger', 'Estado inválido.');
            redirect('pagos/show/' . $id);
        }
        $this->pagoModel->updateEstado($id, $estado);
        setFlash('success', 'Estado del pago actualizado.');
        redirect('pagos/show/' . $id);
    }

    /** Elimina un pago (solo admin). */
    public function delete(int $id): void
    {
        requireAdmin();
        $pago = $this->pagoModel->findById($id);
        if (!$pago) {
            setFlash('warning', 'Pago no encontrado.');
            redirect('pagos');
        }
        $this->pagoModel->delete($id);
        setFlash('success', 'Pago eliminado.');
        redirect('pagos');
    }
}
