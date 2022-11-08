SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
    UNIQUE(nr_colegiado),
    CONSTRAINT PK_Medico PRIMARY KEY (usuario, correo)
);

CREATE TABLE mensajes (
    id_mensaje int AUTO_INCREMENT PRIMARY KEY,
    remitente varchar(20),
    destinatario varchar(20),
    asunto varchar(30),
    contenido varchar(700),
    fecha_envio DATE,
    hora_envio varchar(8),
    leido boolean
);

-- Contraseña: robert
insert into pacientes(usuario, pass, correo, rol, foto) VALUES ('grobert47', '$2y$10$2K.duavabLBa3uyle3vXPOdqIZyXMtisAkFO1XXwFtd8dfv.1JRWu', 'gainarobert47@gmail.com', 1, NULL);
-- Contraseña: paciente2
insert into pacientes(usuario, pass, correo, rol, foto) VALUES ('paciente2', '$2y$10$33nKub9XfyA/RZ4cetDKPufY1PS4qRSSKwKPLLxdv28h5QdGWL/6C', 'paciente2@gmail.com', 1, NULL);

-- Contraseña: ivan; floppa
insert into medicos VALUES ('ivan03', '$2y$10$BKMhfVbuz6je4TqOMulPn.wN2BTRr54pfmZJXbjJ0xSygXv7tdd0m', 'ivancheca@comem.es', 0, NULL, NULL, NULL, NULL, NULL, 0)
, ('floppa', '$2y$10$3PxChLm9kEeN5P47kz00Bu8eQoWqkLT7Jc4iOHea6AbTt0cXaY9Xq', 'floppa@comem.es', 1, NULL, NULL, NULL, NULL, NULL, 0);

insert into mensajes (remitente, destinatario, asunto, contenido, fecha_envio, hora_envio, leido) VALUES ('paciente2', 'ivan03', 'Hola', 'Hola buenas tardeseseseses', '2022-11-08', '08:50:07', false);

insert into mensajes (remitente, destinatario, asunto, contenido, fecha_envio, hora_envio, leido) VALUES ('grobert47', 'floppa', 'Hola2', 'Hola buenas 2', '2022-11-08', '08:54:07', false);

insert into mensajes (remitente, destinatario, asunto, contenido, fecha_envio, hora_envio, leido) VALUES ('paciente2', 'floppa', 'Hola2', 'Hola buenas 2', '2022-11-08', '08:35:07', true);