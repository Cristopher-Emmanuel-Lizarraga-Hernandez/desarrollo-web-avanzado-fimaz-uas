<?php
class Alumno extends Usuario {
    // Additional attribute
    private $matricula;

    // Own constructor
    public function __construct($nombre, $correo, $matricula) {
        // Call parent constructor (Usuario) to validate email and name
        parent::__construct($nombre, $correo);
        
        // Save the student ID (matricula)
        $this->matricula = $matricula;
    }

    // Getter for student ID
    public function getMatricula() {
        return $this->matricula;
    }

    // Specific getRol method for Student
    public function getRol() {
        return "Student";
    }
}
?>