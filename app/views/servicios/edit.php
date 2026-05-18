<?php
/**
 * Vista: Editar servicio
 */
$pageTitle = 'Editar Servicio';
$appUrl    = rtrim(getenv('APP_URL') ?: '', '/');
require_once dirname(__DIR__, 2) . '/helpers/auth.php';

$categoriasDefault = ['Energía', 'Agua', 'Telecom', 'Municipal', 'Seguros'];
$todasCategorias   = array_unique(array_merge($categoriasDefault, $categorias ?? []));
sort($todasCategorias);
?>
<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="app-wrapper">
    <?php require_once dirname(__DIR__) . '/layouts/sidebar.php'; ?>
    <div class="app-main">
        <?php require_once dirname(__DIR__) . '/layouts/navbar.php'; ?>
        <main class="main-content">

            <div class="page-header">
                <div>
                    <h1 class="page-title">Editar Servicio</h1>
                    <p class="page-subtitle"><?= htmlspecialchars($servicio['nombre']) ?></p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= $appUrl ?>/index.php?url=servicios/show/<?= $servicio['id'] ?>"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-eye me-2"></i>Ver
                    </a>
                    <a href="<?= $appUrl ?>/index.php?url=servicios" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert" id="editServicioFlash">
                <?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="card-custom">
                        <div class="card-custom-body">
                            <form action="<?= $appUrl ?>/index.php?url=servicios/edit/<?= $servicio['id'] ?>"
                                  method="POST" id="editServicioForm" novalidate>

                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                           required maxlength="150"
                                           value="<?= htmlspecialchars($_POST['nombre'] ?? $servicio['nombre']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="categoria" class="form-label">Categoría <span class="text-danger">*</span></label>
                                    <select class="form-select" id="categoria" name="categoria" required>
                                        <option value="">Selecciona…</option>
                                        <?php foreach ($todasCategorias as $cat): ?>
                                        <option value="<?= htmlspecialchars($cat) ?>"
                                            <?= (($_POST['categoria'] ?? $servicio['categoria']) === $cat) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat) ?>
                                        </option>
                                        <?php endforeach; ?>
                                        <option value="__nueva__">+ Nueva categoría…</option>
                                    </select>
                                    <input type="text" class="form-control mt-2 d-none" id="nuevaCategoria"
                                           name="nueva_categoria" placeholder="Nueva categoría">
                                </div>

                                <div class="mb-3">
                                    <label for="precio" class="form-label">Precio <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="precio" name="precio"
                                               min="0.01" step="0.01" required
                                               value="<?= htmlspecialchars((string)($_POST['precio'] ?? $servicio['precio'])) ?>">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion"
                                              rows="3" maxlength="1000"><?= htmlspecialchars($_POST['descripcion'] ?? $servicio['descripcion'] ?? '') ?></textarea>
                                </div>

                                <div class="mb-4 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="activo" name="activo"
                                           <?= (bool)($_POST['activo'] ?? $servicio['activo']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="activo">Servicio activo</label>
                                </div>

                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="<?= $appUrl ?>/index.php?url=servicios/delete/<?= $servicio['id'] ?>"
                                       class="btn btn-outline-danger me-auto"
                                       onclick="return confirm('¿Eliminar este servicio?')"
                                       id="deleteServicioFromEditBtn">
                                        <i class="bi bi-trash me-1"></i>Eliminar
                                    </a>
                                    <a href="<?= $appUrl ?>/index.php?url=servicios" class="btn btn-outline-secondary">
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="editServicioSubmitBtn">
                                        <i class="bi bi-save me-2"></i>Guardar Cambios
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
