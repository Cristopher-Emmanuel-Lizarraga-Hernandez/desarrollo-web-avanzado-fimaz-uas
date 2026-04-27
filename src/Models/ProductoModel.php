<?php
/**
 * Model - CRUD with PDO
 * Instituto: UTP
 * Estudiante: Cristopher Hernandez
 * Fecha: 26/04/2026
 */

namespace App\Models;

use App\Database\DatabaseConnection;
use PDOException;
use RuntimeException;

class ProductoModel
{
    private $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::getConnection();
    }

    public function getAll(): array
    {
        try {
            $stmt = $this->db->query("SELECT * FROM productos ORDER BY id DESC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new RuntimeException("Error al obtener productos: " . $e->getMessage());
        }
    }

    public function getById(int $id): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            throw new RuntimeException("Error al buscar producto: " . $e->getMessage());
        }
    }

    public function create(array $data): int
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO productos (nombre, descripcion, precio, cantidad, fecha_creacion) 
                 VALUES (:nombre, :descripcion, :precio, :cantidad, NOW())"
            );
            $stmt->execute([
                'nombre' => $data['nombre'],
                'descripcion' => $data['descripcion'] ?? '',
                'precio' => $data['precio'],
                'cantidad' => $data['cantidad'] ?? 0
            ]);
            return (int) $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new RuntimeException("Error al crear producto: " . $e->getMessage());
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $stmt = $this->db->prepare(
                "UPDATE productos 
                 SET nombre = :nombre, descripcion = :descripcion, 
                     precio = :precio, cantidad = :cantidad 
                 WHERE id = :id"
            );
            return $stmt->execute([
                'id' => $id,
                'nombre' => $data['nombre'],
                'descripcion' => $data['descripcion'] ?? '',
                'precio' => $data['precio'],
                'cantidad' => $data['cantidad'] ?? 0
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException("Error al actualizar producto: " . $e->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM productos WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            throw new RuntimeException("Error al eliminar producto: " . $e->getMessage());
        }
    }

    public function search(string $term): array
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM productos 
                 WHERE nombre LIKE :search OR descripcion LIKE :search 
                 ORDER BY id DESC"
            );
            $stmt->execute(['search' => "%$term%"]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new RuntimeException("Error en la búsqueda: " . $e->getMessage());
        }
    }
}