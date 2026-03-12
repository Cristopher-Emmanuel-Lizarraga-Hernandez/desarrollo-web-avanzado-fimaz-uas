<?php
// Usuario class
class Usuario {

    // private variables of the class
    private $nombre;
    private $correo;

    // constructor
    // this runs when the object is created
    public function __construct($nombre, $correo) {

        // save the name
        $this->nombre = $nombre;

        // check if the email format is correct
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {

            // if the email is wrong, show an error
            throw new Exception("The email is not valid");
        }

        // save the email
        $this->correo = $correo;
    }

    // function to get the name
    public function getNombre(){
        return $this->nombre;
    }

    // function to get the email
    public function getCorreo(){
        return $this->correo;
    }

    // function to know the user role
    // other classes can change this later
    public function getRol(){
        return "User";
    }
}
?>