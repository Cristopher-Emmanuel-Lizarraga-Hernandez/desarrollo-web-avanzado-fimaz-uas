<?php
require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../models/Producto.php";

class ProductoController {
    private $connection;

    public function __construct() {
        $database = new Database();
        $this->connection = $database->getConnection();
    }

    // ── CREATE ────────────────────────────────────────────────
    // ❌ Faltaban los VALUES en el INSERT
    // ❌ "description" → "descripcion" | ❌ "esxistencia" → "existencia"
    // ❌ bindvalue sin ':' en descripcion y existencia
    public function crear(Producto $producto) {
        $sql = "INSERT INTO productos (nombre, descripcion, existencia, precio)
                VALUES (:nombre, :descripcion, :existencia, :precio)";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':nombre',      $producto->getNombre());
        $stmt->bindValue(':descripcion', $producto->getDescripcion());
        $stmt->bindValue(':existencia',  $producto->getExistencia(), PDO::PARAM_INT);
        $stmt->bindValue(':precio',      $producto->getPrecio());

        return $stmt->execute();
    }

    // ── READ — todos ──────────────────────────────────────────
    // ❌ No existía — causaba el error "Call to undefined method obtenerTodos()"
    public function obtenerTodos() {
        $sql  = "SELECT * FROM productos";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $productos = [];
        foreach ($stmt->fetchAll() as $fila) {
            $productos[] = new Producto(
                $fila['id'],
                $fila['nombre'],
                $fila['descripcion'],
                $fila['existencia'],
                $fila['precio']
            );
        }
        return $productos;
    }

    // ── READ — por ID ─────────────────────────────────────────
    // ❌ "obtenerporid" → "obtenerPorId" (camelCase)
    // ❌ Retornaba array, ahora retorna objeto Producto
    public function obtenerPorId($id) {
        $sql  = "SELECT * FROM productos WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $fila = $stmt->fetch();
        if ($fila) {
            return new Producto(
                $fila['id'],
                $fila['nombre'],
                $fila['descripcion'],
                $fila['existencia'],
                $fila['precio']
            );
        }
        return null;
    }

    // ── UPDATE ────────────────────────────────────────────────
    // ❌ "description" → "descripcion" en SQL
    // ❌ "descriptin" → "descripcion" en SQL
    // ❌ Faltaba bindValue de :existencia
    public function actualizar(Producto $producto) {
        $sql = "UPDATE productos
                SET nombre      = :nombre,
                    descripcion = :descripcion,
                    existencia  = :existencia,
                    precio      = :precio
                WHERE id = :id";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':id',          $producto->getId(),         PDO::PARAM_INT);
        $stmt->bindValue(':nombre',      $producto->getNombre());
        $stmt->bindValue(':descripcion', $producto->getDescripcion());
        $stmt->bindValue(':existencia',  $producto->getExistencia(), PDO::PARAM_INT);
        $stmt->bindValue(':precio',      $producto->getPrecio());

        return $stmt->execute();
    }

    // ── DELETE ────────────────────────────────────────────────
    public function eliminar($id) {
        $sql  = "DELETE FROM productos WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // ── BUSCAR ────────────────────────────────────────────────
    // ❌ "descriptin" → "descripcion"
    // ❌ Faltaba bindValue y return
    public function buscar($termino) {
        $sql  = "SELECT * FROM productos
                 WHERE nombre      LIKE :termino
                 OR    descripcion LIKE :termino";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':termino', '%' . $termino . '%');
        $stmt->execute();

        $productos = [];
        foreach ($stmt->fetchAll() as $fila) {
            $productos[] = new Producto(
                $fila['id'],
                $fila['nombre'],
                $fila['descripcion'],
                $fila['existencia'],
                $fila['precio']
            );
        }
        return $productos;
    }
}