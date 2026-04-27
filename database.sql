-- Database: examen_parcial
-- Instituto: UTP
-- Estudiante: Cristopher Hernandez
-- Fecha: 26/04/2026

CREATE DATABASE IF NOT EXISTS examen_parcial DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE examen_parcial;

-- Tabla de productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    cantidad INT NOT NULL DEFAULT 0,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar datos de prueba
INSERT INTO productos (nombre, descripcion, precio, cantidad) VALUES
('Laptop HP', 'Laptop HP Pavilion 15', 450.00, 10),
('Mouse Inalambrico', 'Mouse inalambrico USB', 15.50, 50),
('Teclado Mecanico', 'Teclado mecanico RGB', 75.00, 25),
('Monitor 24"', 'Monitor LED 24 pulgadas', 180.00, 15),
('Auriculares', 'AuricularesBluetooth', 35.00, 30);