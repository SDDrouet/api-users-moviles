-- Crear base de datos
CREATE DATABASE IF NOT EXISTS `usuarios_api`;

-- Usar la base de datos creada
USE `usuarios_api`;

-- Crear tabla de usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Crear un índice para acelerar las búsquedas por username
CREATE INDEX idx_username ON usuarios(username);
