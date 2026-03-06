<?php
require_once "Usuario.php";

$usuario = new Usuario("Crsitopher Emmauel Lizarraga Hernandez", "cemmanuell2020h@gmail.com");

echo "<h1>Práctica 1 - POO en PHP</h1>";
echo "Nombre: " . $usuario->getNombre() . "<br>";
echo "Correo: " . $usuario->getCorreo();
?>