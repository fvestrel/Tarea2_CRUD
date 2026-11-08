-- Crea la base si todavía no existe. utf8mb4 permite guardar tildes y otros caracteres.
CREATE DATABASE IF NOT EXISTS crud_clientes CHARACTER SET utf8mb4;
USE crud_clientes; 

CREATE TABLE IF NOT EXISTS clientes ( 
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    nombre TEXT NOT NULL,
    correo TEXT NOT NULL 
); 
