<?php

require_once __DIR__ . "/../config/Database.php";

class Producto {
    private $id;
    private $nombre;
    private $descripcion;
    private $cantidad;
    private $precio;

    function __construct($id = null, $nombre = "", $descripcion = "", $cantidad = 0, $precio = 0.00) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->cantidad = $cantidad;
        $this->precio = $precio;
    }

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getNombre() {
        return $this->nombre;
    }
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
    
public function getStock() {
        return $this->cantidad;
    }
    public function setStock($cantidad) {
        $this->cantidad = $cantidad;
    }
    public function getPrecio() {
        return $this->precio;
    }
    public function setPrecio($precio) {
        $this->precio = $precio;
    }
}