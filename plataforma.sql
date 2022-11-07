SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- CREACIÓN DE LA BASE DE DATOS --

CREATE DATABASE IF NOT EXISTS MediMadrid;
USE MediMadrid;

-- CREACIÓN DE LAS TABLAS --

CREATE TABLE pacientes (
    usuario varchar(20) NOT NULL,
    pass varchar(255) NOT NULL,
    correo varchar(80) NOT NULL,
    rol int DEFAULT 0, 
    foto BLOB,
    CONSTRAINT PK_Paciente PRIMARY KEY (usuario, correo)
);
-- Contraseña: robert
insert into pacientes(usuario, pass, correo, rol, foto) VALUES ('grobert47', '$2y$10$2K.duavabLBa3uyle3vXPOdqIZyXMtisAkFO1XXwFtd8dfv.1JRWu', 'gainarobert47@gmail.com', 1, NULL);
-- Contraseña: paciente2
insert into pacientes(usuario, pass, correo, rol, foto) VALUES ('paciente2', '$2y$10$33nKub9XfyA/RZ4cetDKPufY1PS4qRSSKwKPLLxdv28h5QdGWL/6C', 'paciente2@gmail.com', 1, NULL);


CREATE TABLE medicos (
    usuario varchar(20) NOT NULL,
    pass varchar(255) NOT NULL,
    correo varchar(80) NOT NULL,
    rol int DEFAULT 0, 
    foto BLOB,
    nr_colegiado varchar(9),
    especialidad varchar(50),
    cv BLOB,
    hospital varchar(80),
    valoracion DECIMAL(5, 2),
    CONSTRAINT PK_Medico PRIMARY KEY (usuario, correo)
);

-- Contraseña: ivan
insert into medicos VALUES ('ivan03', '$2y$10$BKMhfVbuz6je4TqOMulPn.wN2BTRr54pfmZJXbjJ0xSygXv7tdd0m', 'ivancheca@comem.es', 0, NULL, NULL, NULL, NULL, NULL, 0);
-- Contraseña: floppa
insert into medicos VALUES ('floppa', '$2y$10$3PxChLm9kEeN5P47kz00Bu8eQoWqkLT7Jc4iOHea6AbTt0cXaY9Xq', 'floppa@comem.es', 1, NULL, NULL, NULL, NULL, NULL, 0);
