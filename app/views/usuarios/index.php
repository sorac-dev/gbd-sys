<?php
/**
 * Vista: Listado de usuarios (admin)
 */
$pageTitle = 'Usuarios';
$appUrl    = rtrim(getenv('APP_URL') ?: '', '/');
require_once dirname(__DIR__, 2) . '/helpers/auth.php';
?>
<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="app-wrapper">
    <?php require_once dirname(__DIR__) . '/layouts/sidebar.php'; ?>
    <div class="app-main">
        <?php require_once dirname(__DIR__) . '/layouts/navbar.php'; ?>
        <main class="main-content">

            <div class="page-header">
                <div>
                    <h1 class="page-title">Usuarios</h1>
                    <p class="page-subtitle">Gestión de usuarios del sistema</p>
                </div>
                <span class="badge bg-primary fs-6"><?= count($usuarios) ?> usuario(s)</span>
            </div>

            <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert" id="usuariosFlash">
                <i class="bi bi-check-circle me-2"></i><?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
            <?php endif; ?>

            <div class="mb-3">
                <input type="text" id="searchUsuarios" class="form-control"
                       placeholder="Buscar por nombre, email…">
            </div>

            <div class="card-custom">
                <div class="card-custom-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="usuariosTable">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Registrado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $u): ?>
                                <tr class="usuario-row"
                                    data-search="<?= strtolower(htmlspecialchars($u['nombre'] . ' ' . $u['apellido'] . ' ' . $u['email'])) ?>">
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="user-avatar user-avatar--sm">
                                                <?= strtoupper(substr($u['nombre'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="fw-500"><?= htmlspecialchars($u['nombre'] . ' ' . $u['apellido']) ?></div>
                                                <?php if ((int)$u['id'] === currentUserId()): ?>
                                                <small class="text-muted">(tú)</small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td><?= htmlspecialchars($u['telefono'] ?? '—') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $u['rol'] === 'admin' ? 'danger' : 'primary' ?>">
                                            <?= $u['rol'] === 'admin' ? 'Admin' : 'Usuario' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $u['activo'] ? 'success' : 'secondary' ?>">
                                            <?= $u['activo'] ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="<?= $appUrl ?>/index.php?url=usuarios/edit/<?= $u['id'] ?>"
                                               class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <?php if ((int)$u['id'] !== currentUserId()): ?>
                                            <a href="<?= $appUrl ?>/index.php?url=usuarios/delete/<?= $u['id'] ?>"
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('¿Eliminar usuario <?= htmlspecialchars(addslashes($u['nombre'])) ?>?')"
                                               title="Eliminar"
                                               id="deleteUsuario<?= $u['id'] ?>">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
