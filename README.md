# GDB Pagos — Mini Sistema Web de Pago de Servicios

Sistema web desarrollado en **PHP 8.1**, **MySQL**, **PDO** y **Bootstrap 5.3.8** bajo arquitectura **MVC** sin frameworks.

---

## Stack Tecnológico

| Capa       | Tecnología                      |
|------------|----------------------------------|
| Backend    | PHP 8.1 · PDO · MySQL            |
| Frontend   | Bootstrap 5.3.8 · Vanilla CSS/JS |
| Patrón     | MVC (sin frameworks)             |
| BD         | MySQL / MariaDB                  |

---

## Requisitos

- PHP >= 8.1 con extensiones: `pdo`, `pdo_mysql`, `mbstring`
- MySQL >= 5.7 / MariaDB >= 10.4
- Servidor web Apache con `mod_rewrite` habilitado (XAMPP, WAMP, Laragon)
- Acceso a línea de comandos (opcional, para importar SQL)

---

## Instalación Rápida

### 1. Clonar / descomprimir el proyecto

Coloca la carpeta `gdb` dentro del directorio raíz de tu servidor:

```
C:\xampp\htdocs\gdb          (XAMPP Windows)
/var/www/html/gdb            (Linux)
```

### 2. Crear la base de datos

Abre **phpMyAdmin** o tu cliente MySQL favorito y ejecuta:

```sql
-- Primero el schema:
SOURCE /ruta/al/proyecto/database/schema.sql;

-- Luego los datos de prueba:
SOURCE /ruta/al/proyecto/database/seed.sql;
```

O desde la terminal:

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p < database/seed.sql
```

### 3. Configurar el entorno

Edita el archivo `.env` en la raíz del proyecto:

```env
APP_NAME="GDB Pagos"
APP_URL=http://localhost/gdb/public

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=gdb_pagos
DB_USER=root
DB_PASS=          # Tu contraseña de MySQL
```

### 4. Iniciar el servidor

- **XAMPP**: Arranca Apache y MySQL desde el panel de control.
- **Laragon**: Inicia los servicios normalmente.

### 5. Abrir en el navegador

```
http://localhost/gdb/public/index.php
```

---

## Cuentas de Prueba

| Rol           | Email             | Contraseña |
|---------------|-------------------|------------|
| Administrador | admin@gdb.com     | password   |
| Usuario       | maria@gdb.com     | password   |

> **Nota:** Los hashes del seed están generados con `PASSWORD_BCRYPT`. Si la contraseña no funciona, genera un nuevo hash con `password_hash('password', PASSWORD_BCRYPT)` y actualiza la tabla manualmente.

---

## Estructura del Proyecto

```
gdb/
├── app/
│   ├── config/          → Conexión PDO (database.php)
│   ├── controllers/     → AuthController, DashboardController,
│   │                      ServicioController, PagoController, UsuarioController
│   ├── models/          → Usuario, Servicio, Pago (ORM manual)
│   ├── views/
│   │   ├── layouts/     → header, footer, navbar, sidebar
│   │   ├── auth/        → login, register
│   │   ├── dashboard/   → index
│   │   ├── servicios/   → index, create, edit, show
│   │   ├── pagos/       → index, create, show
│   │   └── usuarios/    → index, edit, profile
│   └── helpers/         → auth.php, redirect.php
├── database/
│   ├── schema.sql       → Estructura de tablas
│   └── seed.sql         → Datos de prueba
├── public/
│   ├── css/style.css    → Estilos personalizados
│   ├── js/app.js        → Lógica de interfaz
│   ├── uploads/         → Archivos subidos
│   ├── .htaccess        → Rewrite rules
│   └── index.php        → Front Controller (router)
├── .env                 → Variables de entorno
└── README.md
```

---

## Funcionalidades

### Autenticación
- Registro de usuario con validación
- Login con verificación de contraseña bcrypt
- Cierre de sesión seguro con destrucción de sesión
- Mensajes flash de feedback

### Dashboard
- KPIs para administrador (usuarios, servicios, pagos, recaudado)
- Estadísticas de pagos por estado
- Últimos pagos del sistema / del usuario

### Servicios (CRUD completo — Admin)
- Listado con búsqueda en tiempo real
- Crear, editar y eliminar servicios
- Agrupación por categorías
- Vista detalle con precio y acceso directo al pago

### Pagos
- Nuevo pago con selector de servicio, método y resumen en vivo
- Historial de pagos con búsqueda y filtro por estado
- Vista de comprobante individual
- Actualización de estado (Admin)
- Referencia única autogenerada

### Usuarios (Admin)
- Listado con búsqueda
- Editar datos, rol y estado de cuenta
- Eliminar usuarios (excepto el propio)
- Perfil propio editable para todos los roles

---

## Seguridad

- Consultas preparadas PDO (prevención de SQL Injection)
- Contraseñas hasheadas con `password_hash()` (bcrypt, cost 12)
- Sanitización de salida con `htmlspecialchars()`
- Protección CSRF básica por sesión
- `session_regenerate_id()` al iniciar sesión
- Control de acceso por rol en todos los controladores
- `.htaccess` bloquea acceso a archivos sensibles

---

## Licencia

Proyecto académico — uso libre con fines educativos.
