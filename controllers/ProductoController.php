<?php
require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../models/Producto.php";

class ProductoController {
    private $connection;

    public function __construct() {
        $database = new Database();
        $this->connection = $database->getConnection();
    }

    public function index() {
        return $this->obtenerTodos();
    }

    public function show($id) {
        return $this->obtenerPorId($id);
    }

    public function store($data) {
        $producto = new Producto(
            null,
            $data['nombre'] ?? '',
            $data['descripcion'] ?? '',
            (int)($data['cantidad'] ?? 0),
            (float)($data['precio'] ?? 0)
        );
        return $this->crear($producto);
    }

    public function update($id, $data) {
        $producto = new Producto(
            $id,
            $data['nombre'] ?? '',
            $data['descripcion'] ?? '',
            (int)($data['cantidad'] ?? 0),
            (float)($data['precio'] ?? 0)
        );
        return $this->actualizar($producto);
    }

    public function destroy($id) {
        return $this->eliminar($id);
    }


    public function crear(Producto $producto) {
        $sql = "INSERT INTO productos (nombre, descripcion, cantidad, precio)
                VALUES (:nombre, :descripcion, :cantidad, :precio)";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':nombre',      $producto->getNombre());
        $stmt->bindValue(':descripcion', $producto->getDescripcion());
        $stmt->bindValue(':cantidad',  $producto->getStock(), PDO::PARAM_INT);
        $stmt->bindValue(':precio',      $producto->getPrecio());

        return $stmt->execute();
    }

    public function obtenerTodos() {
        $sql  = "SELECT * FROM productos";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $productos = [];
        foreach ($stmt->fetchAll() as $fila) {
            $productos[] = new Producto(
                $fila['id'] ?? null,
                $fila['nombre'] ?? '',
                $fila['descripcion'] ?? '',
                $fila['cantidad'] ?? 0,
                $fila['precio'] ?? 0
            );
        }
        return $productos;
    }

   
    public function obtenerPorId($id) {
        $sql  = "SELECT * FROM productos WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $fila = $stmt->fetch();
        if ($fila) {
            return new Producto(
                $fila['id'] ?? null,
                $fila['nombre'] ?? '',
                $fila['descripcion'] ?? '',
                $fila['cantidad'] ?? 0,
                $fila['precio'] ?? 0
            );
        }
        return null;
    }

    
    public function actualizar(Producto $producto) {
        $sql = "UPDATE productos
                SET nombre      = :nombre,
                    descripcion = :descripcion,
                    cantidad  = :cantidad,
                    precio      = :precio
                WHERE id = :id";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':id',          $producto->getId(),         PDO::PARAM_INT);
        $stmt->bindValue(':nombre',      $producto->getNombre());
        $stmt->bindValue(':descripcion', $producto->getDescripcion());
        $stmt->bindValue(':cantidad',  $producto->getStock(), PDO::PARAM_INT);
        $stmt->bindValue(':precio',      $producto->getPrecio());

        return $stmt->execute();
    }

    
    public function eliminar($id) {
        $sql  = "DELETE FROM productos WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    
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
                $fila['cantidad'],
                $fila['precio']
            );
        }
        return $productos;
    }
}