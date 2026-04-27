<?php
/**
 * Database Connection - PDO
 * Instituto: UTP
 * Estudiante: Cristopher Hernandez
 * Fecha: 26/04/2026
 */

namespace App\Database;

use PDO;
use PDOException;

class DatabaseConnection
{
    private static ?PDO $connection = null;
    private static string $host = 'localhost';
    private static string $dbname = 'examen_parcial';
    private static string $username = 'root';
    private static string $password = '';
    private static string $charset = 'utf8mb4';

    private function __construct() {}

    public static function getConnection(): PDO
    {
        try {
            if (self::$connection === null) {
                $dsn = sprintf(
                    "mysql:host=%s;dbname=%s;charset=%s",
                    self::$host,
                    self::$dbname,
                    self::$charset
                );

                self::$connection = new PDO(
                    $dsn,
                    self::$username,
                    self::$password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            }
            return self::$connection;
        } catch (PDOException $e) {
            throw new \RuntimeException("Error de conexión: " . $e->getMessage());
        }
    }

    public static function closeConnection(): void
    {
        self::$connection = null;
    }
}