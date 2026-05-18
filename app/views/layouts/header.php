<?php
/**
 * Layout: Header
 * Se incluye al inicio de cada vista.
 */
$appName = getenv('APP_NAME') ?: 'GDB Pagos';
$appUrl  = rtrim(getenv('APP_URL') ?: '', '/');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($appName) ?> — Sistema de pago de servicios en línea">
    <title><?= htmlspecialchars($pageTitle ?? $appName) ?> | <?= htmlspecialchars($appName) ?></title>

    <!-- Bootstrap 5.3.8 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= $appUrl ?>/css/style.css">
</head>
<body class="<?= isLoggedIn() ? 'app-layout' : 'auth-layout' ?>">
