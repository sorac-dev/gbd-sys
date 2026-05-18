<?php
/**
 * Layout: Footer
 * Se incluye al final de cada vista.
 */
$appName = getenv('APP_NAME') ?: 'GDB Pagos';
$appUrl  = rtrim(getenv('APP_URL') ?: '', '/');
?>
    </main><!-- /.main-content -->
</div><!-- /.app-wrapper -->

<footer class="app-footer">
    <div class="container-fluid">
        <span class="text-muted small">
            &copy; <?= date('Y') ?> <strong><?= htmlspecialchars($appName) ?></strong>
            &mdash; Sistema de Pago de Servicios
        </span>
    </div>
</footer>

<!-- Bootstrap 5.3.8 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="<?= $appUrl ?>/js/app.js"></script>
</body>
</html>
