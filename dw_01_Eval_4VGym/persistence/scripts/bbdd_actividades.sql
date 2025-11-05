-- 1. CREACIÓN DE LA BASE DE DATOS

CREATE DATABASE IF NOT EXISTS 4vgym CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2. SELECCIÓN DE LA BASE DE DATOS
USE 4vgym;

-- 3. ELIMINACIÓN Y CREACIÓN DE LA TABLA activities

-- Eliminación de la tabla si existe para asegurar un entorno limpio
DROP TABLE IF EXISTS activities;

-- Creación de la tabla activities
CREATE TABLE activities (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    monitor VARCHAR(100) NOT NULL,
    place VARCHAR(50) NOT NULL,
    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 4. INSERCIÓN DE DATOS DE EJEMPLO

-- Datos de ejemplo basados en las capturas y requisitos del examen
INSERT INTO activities (type, monitor, place, date) VALUES
-- Referencia a la imagen de Spinning (Aula 14)
('Spinning', 'María Hernandez', 'Aula 14', '2023-11-13 19:00:00'),
-- Referencia a la edición de BodyPump (Aula 15)
('BodyPump', 'Miguel Goyena 777ddd', 'Aula 15', '2025-12-26 09:53:32'),
-- Referencia a la actividad con tipo 'aaa' / Pilates (Sala B)
('Pilates', 'Monitor Prueba', 'Sala B', '2025-11-06 09:42:00'),
-- Actividad adicional para asegurar datos
('Spinning', 'Otro Monitor', 'Aula 14', '2025-12-01 10:00:00');