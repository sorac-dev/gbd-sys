-- ============================================================
-- Mini Sistema Web de Pago de Servicios
-- Schema principal
-- ============================================================

CREATE DATABASE IF NOT EXISTS gdb_pagos
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE gdb_pagos;

-- -------------------------------------------------------
-- Tabla: usuarios
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin','user') NOT NULL DEFAULT 'user',
    telefono VARCHAR(20) DEFAULT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Tabla: servicios
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS servicios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT DEFAULT NULL,
    categoria VARCHAR(80) NOT NULL,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    activo TINYINT(1) NOT NULL DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- Tabla: pagos
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS pagos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT UNSIGNED NOT NULL,
    servicio_id INT UNSIGNED NOT NULL,

    monto DECIMAL(10,2) NOT NULL,

    referencia VARCHAR(50) NOT NULL UNIQUE,

    estado ENUM(
        'pendiente',
        'completado',
        'fallido',
        'cancelado'
    ) NOT NULL DEFAULT 'pendiente',

    metodo_pago ENUM(
        'efectivo',
        'tarjeta',
        'transferencia'
    ) NOT NULL DEFAULT 'efectivo',

    notas TEXT DEFAULT NULL,

    fecha_pago TIMESTAMP NULL DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_pagos_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_pagos_servicio
        FOREIGN KEY (servicio_id)
        REFERENCES servicios(id)
        ON DELETE RESTRICT

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;