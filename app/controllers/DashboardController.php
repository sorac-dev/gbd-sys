<?php
/**
 * DashboardController — Página principal post-login
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/models/Usuario.php';
require_once dirname(__DIR__) . '/models/Servicio.php';
require_once dirname(__DIR__) . '/models/Pago.php';
require_once dirname(__DIR__) . '/helpers/auth.php';
require_once dirname(__DIR__) . '/helpers/redirect.php';

class DashboardController
{
    private Usuario  $usuarioModel;
    private Servicio $servicioModel;
    private Pago     $pagoModel;

    public function __construct()
    {
        $this->usuarioModel  = new Usuario();
        $this->servicioModel = new Servicio();
        $this->pagoModel     = new Pago();
    }

    public function index(): void
    {
        requireAuth();

        $flash = getFlash();

        if (isAdmin()) {
            // Datos para el admin
            $totalUsuarios  = $this->usuarioModel->count();
            $totalServicios = $this->servicioModel->count();
            $totalPagos     = $this->pagoModel->count();
            $totalRecaudado = $this->pagoModel->totalRecaudado();
            $estadisticas   = $this->pagoModel->estadisticasPorEstado();
            $ultimosPagos   = $this->pagoModel->ultimos(8);
        } else {
            // Datos para usuario regular
            $totalUsuarios  = null;
            $totalServicios = $this->servicioModel->count();
            $totalPagos     = null;
            $totalRecaudado = null;
            $estadisticas   = [];
            $ultimosPagos   = $this->pagoModel->byUsuario(currentUserId());
            $ultimosPagos   = array_slice($ultimosPagos, 0, 5);
        }

        require_once dirname(__DIR__) . '/views/dashboard/index.php';
    }
}
