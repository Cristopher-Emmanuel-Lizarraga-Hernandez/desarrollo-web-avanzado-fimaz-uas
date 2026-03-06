<?php

class Usuario {

    // Atributos privados (encapsulamiento)
    private $nombre;
    private $correo;

    // Constructor
    public function __construct($nombre, $correo) {
        $this->nombre = $nombre;
        $this->correo = $correo;
    }

    // Getter nombre
    public function getNombre() {
        return $this->nombre;
    }

    // Getter correo
    public function getCorreo() {
        return $this->correo;
    }

    // Setter nombre
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    // Setter correo
    public function setCorreo($correo) {
        $this->correo = $correo;
    }
}

?>