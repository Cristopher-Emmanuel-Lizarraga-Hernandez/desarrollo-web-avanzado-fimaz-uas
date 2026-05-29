<?php

class Productos {
    
    // Conexión a la base de datos
    private $conn;
    
    // Tabla
    private $tabla = "productos";
    
    // Propiedades del producto
    public $idProducto;
    public $nombreproducto;
    public $descripcion;
    public $precioCompra;
    public $precioVenta;
    public $existencia;
    
    // Constructor que recibe la conexión
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // =====================================================
    // OBTENER TODOS LOS PRODUCTOS
    // =====================================================
    public function getProductos() {
        $consultaSQL = "SELECT idProducto, nombreproducto, descripcion, precioCompra, 
                        precioVenta, existencia
                        FROM " . $this->tabla;
        
        $stmt = $this->conn->prepare($consultaSQL);
        $stmt->execute();
        
        return $stmt;
    }
    
    // =====================================================
    // OBTENER UN PRODUCTO POR ID
    // =====================================================
    public function getProducto() {
        $consultaSQL = "SELECT 
                        idProducto,
                        nombreproducto,
                        descripcion,
                        precioCompra,
                        precioVenta,
                        existencia
                        FROM
                        " . $this->tabla . "
                        WHERE
                        idProducto = ?
                        LIMIT 0,1";
        
        $stmt = $this->conn->prepare($consultaSQL);
        $stmt->bindParam(1, $this->idProducto);
        $stmt->execute();
        
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($dataRow) {
            $this->nombreproducto = $dataRow['nombreproducto'];
            $this->descripcion = $dataRow['descripcion'];
            $this->precioCompra = $dataRow['precioCompra'];
            $this->precioVenta = $dataRow['precioVenta'];
            $this->existencia = $dataRow['existencia'];
            return true;
        }
        
        return false;
    }
    
    // =====================================================
    // CREAR UN NUEVO PRODUCTO
    // =====================================================
    public function setProductos() {
        $consultaSQL = "INSERT INTO
                        " . $this->tabla . "
                        SET
                        nombreproducto = :nombreproducto,
                        descripcion = :descripcion,
                        precioCompra = :precioCompra,
                        precioVenta = :precioVenta,
                        existencia = :existencia";
        
        $stmt = $this->conn->prepare($consultaSQL);
        
        // Limpiar datos
        $this->nombreproducto = htmlspecialchars(strip_tags($this->nombreproducto));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precioCompra = htmlspecialchars(strip_tags($this->precioCompra));
        $this->precioVenta = htmlspecialchars(strip_tags($this->precioVenta));
        $this->existencia = htmlspecialchars(strip_tags($this->existencia));
        
        // Enlazar parámetros
        $stmt->bindParam(':nombreproducto', $this->nombreproducto);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':precioCompra', $this->precioCompra);
        $stmt->bindParam(':precioVenta', $this->precioVenta);
        $stmt->bindParam(':existencia', $this->existencia);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // =====================================================
    // ACTUALIZAR UN PRODUCTO
    // =====================================================
    public function updateProducto() {
        $consultaSQL = "UPDATE " . $this->tabla . "
                        SET
                        nombreproducto = :nombreproducto,
                        descripcion = :descripcion,
                        precioCompra = :precioCompra,
                        precioVenta = :precioVenta,
                        existencia = :existencia
                        WHERE
                        idProducto = :idProducto";
        
        $stmt = $this->conn->prepare($consultaSQL);
        
        // Limpiar datos
        $this->idProducto = htmlspecialchars(strip_tags($this->idProducto));
        $this->nombreproducto = htmlspecialchars(strip_tags($this->nombreproducto));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precioCompra = htmlspecialchars(strip_tags($this->precioCompra));
        $this->precioVenta = htmlspecialchars(strip_tags($this->precioVenta));
        $this->existencia = htmlspecialchars(strip_tags($this->existencia));
        
        // Enlazar parámetros
        $stmt->bindParam(':idProducto', $this->idProducto);
        $stmt->bindParam(':nombreproducto', $this->nombreproducto);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':precioCompra', $this->precioCompra);
        $stmt->bindParam(':precioVenta', $this->precioVenta);
        $stmt->bindParam(':existencia', $this->existencia);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // =====================================================
    // ELIMINAR UN PRODUCTO
    // =====================================================
    public function borrarProducto() {
        $consultaSQL = "DELETE FROM " . $this->tabla . " WHERE idProducto = ?";
        $stmt = $this->conn->prepare($consultaSQL);
        
        $this->idProducto = htmlspecialchars(strip_tags($this->idProducto));
        $stmt->bindParam(1, $this->idProducto);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}
?>