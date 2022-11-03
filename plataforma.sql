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
    foto BLOB,
    CONSTRAINT PK_Paciente PRIMARY KEY (usuario, correo)
);

insert into pacientes (usuario, pass, correo, foto) VALUES ('grobert47', '$2y$10$2K.duavabLBa3uyle3vXPOdqIZyXMtisAkFO1XXwFtd8dfv.1JRWu', 'gainarobert47@gmail.com', NULL);

CREATE TABLE medicos (
    usuario varchar(20) NOT NULL,
    pass varchar(255) NOT NULL,
    correo varchar(80) NOT NULL,
    foto BLOB,
    nr_colegiado varchar(9),
    especialidad varchar(50),
    cv BLOB,
    hospital varchar(80),
    valoracion DECIMAL(5, 2),
    UNIQUE (nr_colegiado),
    CONSTRAINT PK_Medico PRIMARY KEY (usuario, correo)
);

insert into medicos (usuario, pass, correo) VALUES ('ivan03', '$2y$10$BKMhfVbuz6je4TqOMulPn.wN2BTRr54pfmZJXbjJ0xSygXv7tdd0m', 'ivancheca@comem.es');

