-- ============================================================
-- seed.sql
-- Datos iniciales para Mini Sistema Web de Pago de Servicios
-- Compatible con MySQL / MariaDB
-- ============================================================

USE gdb_pagos;

SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- Limpiar tablas
-- ------------------------------------------------------------

TRUNCATE TABLE pagos;
TRUNCATE TABLE servicios;
TRUNCATE TABLE usuarios;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- USUARIOS
-- ============================================================

INSERT INTO usuarios (
    id,
    nombre,
    apellido,
    email,
    password,
    rol,
    telefono,
    activo,
    created_at,
    updated_at
) VALUES

(
    1,
    'Carlos',
    'Esteban',
    'admin@gdb.com',
    '$2y$12$/yCOU.tk1QBLDDQm0ORYxO920eGXBpoDJKFH4C5Vdq.fWvHUBJWQ6',
    'admin',
    '3000000000',
    1,
    '2026-05-18 07:08:41',
    '2026-05-18 07:10:17'
),

(
    2,
    'Maria',
    'Torres',
    'maria@gdb.com',
    '$2y$12$fBGLOoM2u1eOZ9u20/d3O.t1xXig0HXoChgMpq/OC4J1LRkO80WHK',
    'user',
    '3040000000',
    1,
    '2026-05-18 07:09:19',
    '2026-05-18 07:09:19'
);

-- ============================================================
-- SERVICIOS
-- ============================================================

INSERT INTO servicios (
    id,
    nombre,
    descripcion,
    categoria,
    precio,
    activo,
    created_at,
    updated_at
) VALUES

(
    1,
    'Luz Eléctrica',
    'Pago mensual del servicio de energía eléctrica.',
    'Energía',
    185000.00,
    1,
    '2026-05-18 07:10:03',
    '2026-05-18 07:10:03'
),

(
    2,
    'Agua Potable',
    'Suministro de agua potable domiciliar.',
    'Agua',
    95000.00,
    1,
    '2026-05-18 07:10:03',
    '2026-05-18 07:10:03'
),

(
    3,
    'Gas Natural',
    'Suministro de gas natural residencial.',
    'Energía',
    78000.00,
    1,
    '2026-05-18 07:10:03',
    '2026-05-18 07:10:03'
),

(
    4,
    'Internet Hogar',
    'Conectividad a internet de banda ancha para el hogar.',
    'Telecom',
    145000.00,
    1,
    '2026-05-18 07:10:03',
    '2026-05-18 07:10:03'
),

(
    5,
    'Telefonía Fija',
    'Servicio de telefonía fija residencial.',
    'Telecom',
    45000.00,
    1,
    '2026-05-18 07:10:03',
    '2026-05-18 07:10:03'
),

(
    6,
    'Televisión Cable',
    'Paquete de televisión por cable con 150 canales.',
    'Telecom',
    110000.00,
    1,
    '2026-05-18 07:10:03',
    '2026-05-18 07:10:03'
),

(
    7,
    'Recolección Basura',
    'Servicio municipal de recolección de residuos.',
    'Municipal',
    35000.00,
    1,
    '2026-05-18 07:10:03',
    '2026-05-18 07:10:03'
),

(
    8,
    'Mantenimiento Vial',
    'Contribución al mantenimiento de calles y vías.',
    'Municipal',
    25000.00,
    1,
    '2026-05-18 07:10:03',
    '2026-05-18 07:10:03'
),

(
    9,
    'Seguro Hogar',
    'Póliza de seguro para vivienda.',
    'Seguros',
    320000.00,
    1,
    '2026-05-18 07:10:03',
    '2026-05-18 07:10:03'
),

(
    10,
    'Seguro Vehículo',
    'Póliza de seguro vehicular básico.',
    'Seguros',
    480000.00,
    1,
    '2026-05-18 07:10:03',
    '2026-05-18 07:10:03'
);

-- ============================================================
-- PAGOS
-- ============================================================

INSERT INTO pagos (
    id,
    usuario_id,
    servicio_id,
    monto,
    referencia,
    estado,
    metodo_pago,
    notas,
    fecha_pago,
    created_at,
    updated_at
) VALUES

(
    1,
    2,
    2,
    95000.00,
    'REF-A3360261',
    'pendiente',
    'efectivo',
    NULL,
    NULL,
    '2026-05-18 07:13:05',
    '2026-05-18 07:13:05'
),

(
    2,
    2,
    10,
    480000.00,
    'REF-C37FD105',
    'fallido',
    'efectivo',
    'test',
    NULL,
    '2026-05-18 07:13:32',
    '2026-05-18 07:14:30'
),

(
    3,
    2,
    5,
    45000.00,
    'REF-1CC285DF',
    'completado',
    'tarjeta',
    'phoal',
    '2026-05-18 14:14:20',
    '2026-05-18 07:13:42',
    '2026-05-18 07:14:20'
),

(
    4,
    2,
    4,
    145000.00,
    'REF-5CB89C5D',
    'cancelado',
    'tarjeta',
    'lkmk',
    NULL,
    '2026-05-18 07:14:53',
    '2026-05-18 07:14:53'
),

(
    5,
    2,
    4,
    145000.00,
    'REF-7506DD89',
    'pendiente',
    'efectivo',
    'ok',
    NULL,
    '2026-05-18 07:17:24',
    '2026-05-18 07:17:24'
),

(
    6,
    2,
    2,
    95000.00,
    'REF-8171EE20',
    'pendiente',
    'tarjeta',
    NULL,
    NULL,
    '2026-05-18 07:17:35',
    '2026-05-18 07:17:35'
);

-- ============================================================
-- Reiniciar AUTO_INCREMENT
-- ============================================================

ALTER TABLE usuarios AUTO_INCREMENT = 3;
ALTER TABLE servicios AUTO_INCREMENT = 11;
ALTER TABLE pagos AUTO_INCREMENT = 7;