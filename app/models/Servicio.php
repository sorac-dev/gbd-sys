<?php
/**
 * Modelo Servicio
 * Mapeo objeto-relacional manual sobre la tabla `servicios`.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/database.php';

class Servicio
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ── Consultas ───────────────────────────────────────────────────────────

    /** Devuelve todos los servicios (opcionalmente solo activos). */
    public function all(bool $soloActivos = false): array
    {
        $where = $soloActivos ? 'WHERE activo = 1' : '';
        $stmt  = $this->db->query(
            "SELECT * FROM servicios $where ORDER BY categoria, nombre"
        );
        return $stmt->fetchAll();
    }

    /** Busca un servicio por ID. */
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM servicios WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /** Lista de categorías disponibles. */
    public function categorias(): array
    {
        $stmt = $this->db->query(
            'SELECT DISTINCT categoria FROM servicios ORDER BY categoria'
        );
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /** Servicios agrupados por categoría. */
    public function porCategoria(): array
    {
        $all    = $this->all(true);
        $grupos = [];
        foreach ($all as $s) {
            $grupos[$s['categoria']][] = $s;
        }
        return $grupos;
    }

    /** Total de servicios activos. */
    public function count(): int
    {
        return (int) $this->db->query(
            'SELECT COUNT(*) FROM servicios WHERE activo = 1'
        )->fetchColumn();
    }

    // ── Mutaciones ──────────────────────────────────────────────────────────

    /** Crea un nuevo servicio. */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO servicios (nombre, descripcion, categoria, precio, activo)
                  VALUES (:nombre, :descripcion, :categoria, :precio, :activo)'
        );
        $stmt->execute([
            ':nombre'      => $data['nombre'],
            ':descripcion' => $data['descripcion'] ?? null,
            ':categoria'   => $data['categoria'],
            ':precio'      => $data['precio'],
            ':activo'      => $data['activo'] ?? 1,
        ]);
        return (int) $this->db->lastInsertId();
    }

    /** Actualiza un servicio. */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE servicios
                SET nombre      = :nombre,
                    descripcion = :descripcion,
                    categoria   = :categoria,
                    precio      = :precio,
                    activo      = :activo
              WHERE id = :id'
        );
        return $stmt->execute([
            ':nombre'      => $data['nombre'],
            ':descripcion' => $data['descripcion'] ?? null,
            ':categoria'   => $data['categoria'],
            ':precio'      => $data['precio'],
            ':activo'      => $data['activo'] ?? 1,
            ':id'          => $id,
        ]);
    }

    /** Elimina un servicio. */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM servicios WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
