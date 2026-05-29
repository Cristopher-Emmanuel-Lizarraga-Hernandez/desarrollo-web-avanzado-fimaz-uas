<?php

class Database {
    
    // Propiedades privadas para la conexión
    private $host = "localhost";
    private $db_name = "api";
    private $username = "root";
    private $password = "";
    
    // Propiedad pública para la conexión
    public $conn;
    
    // =====================================================
    // MÉTODO CONSTRUCTOR - Se ejecuta al crear la clase
    // =====================================================
    public function __construct() {
        // Aquí puedes inicializar lo que necesites
    }
    
    // =====================================================
    // MÉTODO getConnection() - Retorna la conexión
    // =====================================================
    public function getConnection() {
        $this->conn = null;
        
        try {
            // Crear conexión PDO
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            
            // Configurar el conjunto de caracteres a UTF-8
            $this->conn->exec("set names utf8");
            
        } catch (PDOException $exception) {
            // Si hay error, mostrar el mensaje
            echo "Error en conexión a la base de datos: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}

?>