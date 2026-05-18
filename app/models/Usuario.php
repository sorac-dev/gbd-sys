<?php
/**
 * Modelo Usuario
 * Mapeo objeto-relacional manual sobre la tabla `usuarios`.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/database.php';

class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ── Consultas ───────────────────────────────────────────────────────────

    /** Devuelve todos los usuarios. */
    public function all(): array
    {
        $stmt = $this->db->query(
            'SELECT id, nombre, apellido, email, rol, telefono, activo, created_at
               FROM usuarios
              ORDER BY created_at DESC'
        );
        return $stmt->fetchAll();
    }

    /**
     * Busca un usuario por su ID.
     * @return array|false
     */
    public function findById(int $id)
    {
        $stmt = $this->db->prepare(
            'SELECT id, nombre, apellido, email, rol, telefono, activo, created_at, updated_at
               FROM usuarios
              WHERE id = :id
              LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Busca un usuario por email (incluye password para autenticación).
     * @return array|false
     */
    public function findByEmail(string $email)
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM usuarios WHERE email = :email LIMIT 1'
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    /** Comprueba si el email ya existe (para registro). */
    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM usuarios WHERE email = :email'
        );
        $stmt->execute([':email' => $email]);
        return (bool) $stmt->fetchColumn();
    }

    // ── Mutaciones ──────────────────────────────────────────────────────────

    /** Registra un nuevo usuario. */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO usuarios (nombre, apellido, email, password, rol, telefono)
                  VALUES (:nombre, :apellido, :email, :password, :rol, :telefono)'
        );
        $stmt->execute([
            ':nombre'   => $data['nombre'],
            ':apellido' => $data['apellido'],
            ':email'    => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]),
            ':rol'      => $data['rol']      ?? 'user',
            ':telefono' => $data['telefono'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    /** Actualiza datos de un usuario. */
    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        $allowed = ['nombre', 'apellido', 'email', 'telefono', 'activo', 'rol'];
        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }

        if (isset($data['password']) && $data['password'] !== '') {
            $fields[] = 'password = :password';
            $params[':password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        }

        if (empty($fields)) {
            return false;
        }

        $sql  = 'UPDATE usuarios SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /** Elimina un usuario. */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM usuarios WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    /** Total de usuarios. */
    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
    }
}
