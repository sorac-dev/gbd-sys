<?php
/**
 * Modelo Pago
 * Mapeo objeto-relacional manual sobre la tabla `pagos`.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/database.php';

class Pago
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ── Consultas ───────────────────────────────────────────────────────────

    /** Todos los pagos con datos de usuario y servicio. */
    public function all(): array
    {
        $stmt = $this->db->query(
            'SELECT p.*,
                    CONCAT(u.nombre, " ", u.apellido) AS usuario_nombre,
                    u.email AS usuario_email,
                    s.nombre AS servicio_nombre,
                    s.categoria AS servicio_categoria
               FROM pagos p
               JOIN usuarios u  ON u.id = p.usuario_id
               JOIN servicios s ON s.id = p.servicio_id
              ORDER BY p.created_at DESC'
        );
        return $stmt->fetchAll();
    }

    /** Pagos de un usuario específico. */
    public function byUsuario(int $usuarioId): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*,
                    s.nombre     AS servicio_nombre,
                    s.categoria  AS servicio_categoria
               FROM pagos p
               JOIN servicios s ON s.id = p.servicio_id
              WHERE p.usuario_id = :uid
              ORDER BY p.created_at DESC'
        );
        $stmt->execute([':uid' => $usuarioId]);
        return $stmt->fetchAll();
    }

    /**
     * Busca un pago por ID (incluye datos relacionados).
     * @return array|false
     */
    public function findById(int $id)
    {
        $stmt = $this->db->prepare(
            'SELECT p.*,
                    CONCAT(u.nombre, " ", u.apellido) AS usuario_nombre,
                    u.email AS usuario_email,
                    s.nombre    AS servicio_nombre,
                    s.categoria AS servicio_categoria,
                    s.precio    AS servicio_precio
               FROM pagos p
               JOIN usuarios u  ON u.id = p.usuario_id
               JOIN servicios s ON s.id = p.servicio_id
              WHERE p.id = :id
              LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Busca por referencia.
     * @return array|false
     */
    public function findByReferencia(string $ref)
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM pagos WHERE referencia = :ref LIMIT 1'
        );
        $stmt->execute([':ref' => $ref]);
        return $stmt->fetch();
    }

    /** Total de pagos. */
    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM pagos')->fetchColumn();
    }

    /** Total recaudado (solo pagos completados). */
    public function totalRecaudado(): float
    {
        return (float) $this->db->query(
            "SELECT COALESCE(SUM(monto), 0) FROM pagos WHERE estado = 'completado'"
        )->fetchColumn();
    }

    /** Estadísticas por estado. */
    public function estadisticasPorEstado(): array
    {
        $stmt = $this->db->query(
            "SELECT estado, COUNT(*) AS total, COALESCE(SUM(monto), 0) AS monto
               FROM pagos
              GROUP BY estado"
        );
        return $stmt->fetchAll();
    }

    /** Últimos N pagos. */
    public function ultimos(int $n = 5): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*,
                    CONCAT(u.nombre, " ", u.apellido) AS usuario_nombre,
                    s.nombre AS servicio_nombre
               FROM pagos p
               JOIN usuarios u  ON u.id = p.usuario_id
               JOIN servicios s ON s.id = p.servicio_id
              ORDER BY p.created_at DESC
              LIMIT :n'
        );
        $stmt->bindValue(':n', $n, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ── Mutaciones ──────────────────────────────────────────────────────────

    /** Genera una referencia única. */
    public function generarReferencia(): string
    {
        do {
            $ref = 'REF-' . strtoupper(bin2hex(random_bytes(4)));
        } while ($this->findByReferencia($ref));
        return $ref;
    }

    /** Crea un nuevo pago. */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO pagos (usuario_id, servicio_id, monto, referencia, estado, metodo_pago, notas, fecha_pago)
                  VALUES (:usuario_id, :servicio_id, :monto, :referencia, :estado, :metodo_pago, :notas, :fecha_pago)'
        );
        $estado    = $data['estado']    ?? 'pendiente';
        $fechaPago = ($estado === 'completado') ? date('Y-m-d H:i:s') : null;

        $stmt->execute([
            ':usuario_id'  => $data['usuario_id'],
            ':servicio_id' => $data['servicio_id'],
            ':monto'       => $data['monto'],
            ':referencia'  => $data['referencia'] ?? $this->generarReferencia(),
            ':estado'      => $estado,
            ':metodo_pago' => $data['metodo_pago'],
            ':notas'       => $data['notas']       ?? null,
            ':fecha_pago'  => $data['fecha_pago']  ?? $fechaPago,
        ]);
        return (int) $this->db->lastInsertId();
    }

    /** Actualiza el estado de un pago. */
    public function updateEstado(int $id, string $estado): bool
    {
        $fechaPago = ($estado === 'completado') ? date('Y-m-d H:i:s') : null;
        $stmt = $this->db->prepare(
            'UPDATE pagos SET estado = :estado, fecha_pago = :fecha_pago WHERE id = :id'
        );
        return $stmt->execute([
            ':estado'     => $estado,
            ':fecha_pago' => $fechaPago,
            ':id'         => $id,
        ]);
    }

    /** Elimina un pago. */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM pagos WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
