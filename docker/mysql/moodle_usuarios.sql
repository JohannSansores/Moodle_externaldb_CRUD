SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS moodle_usuarios
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE moodle_usuarios;

-- =====================
-- DROP EXISTING OBJECTS
-- =====================
DROP VIEW IF EXISTS vw_usuarios_moodle;
DROP TABLE IF EXISTS usuarios_externos;
DROP TABLE IF EXISTS cat_dependencias;
DROP TABLE IF EXISTS cat_programas;
DROP TABLE IF EXISTS cat_roles;
DROP TABLE IF EXISTS cat_semestres;

-- =====================
-- CATÁLOGOS
-- =====================

CREATE TABLE cat_dependencias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE cat_programas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(200) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE cat_roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE cat_semestres (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(20) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- =====================
-- USUARIOS EXTERNOS
-- =====================

CREATE TABLE usuarios_externos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  firstname VARCHAR(100) NOT NULL,
  lastname VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  id_dependencia INT NOT NULL,
  id_programa INT NOT NULL,
  id_rol INT NOT NULL,
  id_semestre INT NOT NULL,
  fechacreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_dependencia FOREIGN KEY (id_dependencia) REFERENCES cat_dependencias(id),
  CONSTRAINT fk_programa    FOREIGN KEY (id_programa)    REFERENCES cat_programas(id),
  CONSTRAINT fk_rol         FOREIGN KEY (id_rol)         REFERENCES cat_roles(id),
  CONSTRAINT fk_semestre    FOREIGN KEY (id_semestre)    REFERENCES cat_semestres(id)
) ENGINE=InnoDB;

-- =====================
-- DATA: DEPENDENCIAS
-- =====================

INSERT INTO cat_dependencias (nombre) VALUES
('Facultad de Arquitectura'),
('Facultad de Contaduría y Administración'),
('Facultad de Derecho'),
('Facultad de Economía'),
('Facultad de Psicología'),
('Facultad de Ciencias Antropológicas'),
('Facultad de Educación'),
('Facultad de Ingeniería'),
('Facultad de Ingeniería Química'),
('Facultad de Matemáticas'),
('Facultad de Medicina'),
('Facultad de Enfermería'),
('Facultad de Odontología'),
('Facultad de Química'),
('Facultad de Medicina Veterinaria y Zootecnia'),
('Escuela Preparatoria 1'),
('Escuela Preparatoria 2'),
('Unidad Académica de Bachillerato con Interacción Comunitaria'),
('Unidad Multidisciplinaria de Tizimín'),
('Unidad Académica de Educación Virtual (UAEV)'),
('Centro Institucional de Lenguas'),
('Dirección General de Desarrollo Académico'),
('Dirección de Planeación y Efectividad Institucional'),
('Dirección de Finanzas y Administración'),
('Secretaría General'),
('Rectoría'),
('Otro'),
('No definido');

-- =====================
-- DATA: ROLES
-- =====================

INSERT INTO cat_roles (nombre) VALUES
('--Selecciona--'),
('Administrativo'),
('Académico'),
('Manual'),
('Estudiante'),
('Investigador'),
('Otro');

-- =====================
-- DATA: SEMESTRES
-- =====================

INSERT INTO cat_semestres (nombre) VALUES
('No Aplica'),
('1'),('2'),('3'),('4'),('5'),
('6'),('7'),('8'),('9'),
('10'),('11'),('12');

-- =====================
-- DATA: PROGRAMAS
-- =====================

INSERT INTO cat_programas (nombre) VALUES
('No Aplica'),
('LICENCIATURA EN ARQUITECTURA'),
('LICENCIATURA EN ARTES VISUALES'),
('LICENCIATURA EN ARQUEOLOGÍA'),
('LICENCIATURA EN ANTROPOLOGÍA SOCIAL'),
('LICENCIATURA EN ENFERMERÍA'),
('LICENCIATURA EN TRABAJO SOCIAL'),
('LICENCIATURA EN MERCADOTECNIA Y NEGOCIOS INTERNACIONALES'),
('CONTADOR PÚBLICO'),
('LICENCIATURA EN ADMINISTRACIÓN'),
('LICENCIATURA EN ADMINISTRACIÓN DE TECNOLOGÍAS DE INFORMACIÓN'),
('LICENCIATURA EN PSICOLOGÍA'),
('LICENCIATURA EN COMUNICACIÓN SOCIAL'),
('LICENCIATURA EN LITERATURA LATINOAMERICANA'),
('LICENCIATURA EN BIOLOGÍA'),
('LICENCIATURA EN MEDICINA VETERINARIA Y ZOOTECNIA'),
('LICENCIATURA EN AGROECOLOGÍA'),
('LICENCIATURA EN BIOLOGÍA MARINA'),
('CIRUJANO DENTISTA'),
('LICENCIATURA EN DERECHO'),
('LICENCIATURA EN DERECHO (VIRTUAL)'),
('LICENCIATURA EN NUTRICIÓN'),
('MÉDICO CIRUJANO'),
('LICENCIATURA EN HISTORIA'),
('LICENCIATURA EN INGENIERÍA DE SOFTWARE'),
('LICENCIATURA EN INGENIERÍA EN SOFTWARE (VIRTUAL)'),
('LICENCIATURA EN CIENCIAS DE LA COMPUTACIÓN'),
('LICENCIATURA EN INGENIERÍA EN COMPUTACIÓN'),
('LICENCIATURA EN ENSEÑANZA DE LAS MATEMÁTICAS'),
('LICENCIATURA EN ACTUARÍA'),
('LICENCIATURA EN INGENIERÍA INDUSTRIAL LOGÍSTICA'),
('LICENCIATURA EN INGENIERÍA QUÍMICA INDUSTRIAL'),
('LICENCIATURA INSTITUCIONAL EN QUÍMICA APLICADA'),
('LICENCIATURA EN EDUCACIÓN'),
('QUÍMICO FARMACÉUTICO BIÓLOGO'),
('LICENCIATURA EN ENSEÑANZA DEL IDIOMA INGLES'),
('LICENCIATURA EN ECONOMÍA'),
('LICENCIATURA EN COMERCIO INTERNACIONAL'),
('LICENCIATURA EN INGENIERÍA EN MECATRÓNICA'),
('LICENCIATURA EN INGENIERÍA EN ENERGÍAS RENOVABLES'),
('LICENCIATURA EN INGENIERÍA FÍSICA'),
('LICENCIATURA EN INGENIERÍA CIVIL'),
('LICENCIATURA EN INGENIERÍA EN BIOTECNOLOGÍA'),
('LICENCIATURA EN INGENIERÍA EN ALIMENTOS'),
('LICENCIATURA EN TURISMO'),
('LICENCIATURA EN DISEÑO DEL HÁBITAT'),
('LICENCIATURA EN MATEMÁTICAS'),
('LICENCIATURA EN REHABILITACIÓN'),
('MAESTRÍA EN ARQUITECTURA'),
('MAESTRÍA EN INGENIERÍA DE OPERACIONES ESTRATÉGICAS'),
('CURSO COLOQUIAL DE INGLÉS'),
('CURSO COLOQUIAL DE FRANCÉS'),
('CURSO COLOQUIAL DE ITALIANO'),
('CURSO COLOQUIAL DE MAYA'),
('CURSO COLOQUIAL DE ALEMÁN'),
('INGLÉS VIRTUAL'),
('ASIGNATURA LIBRE'),
('MOVILIDAD'),
('Doctorado en Ciencias Matemáticas');

-- =====================
-- VIEW FOR MOODLE
-- =====================

CREATE VIEW vw_usuarios_moodle AS
SELECT
  u.id,
  u.username,
  u.password,
  u.firstname,
  u.lastname,
  u.email,
  d.nombre AS dependencia,
  p.nombre AS programa,
  r.nombre AS rol,
  s.nombre AS semestre
FROM usuarios_externos u
JOIN cat_dependencias d ON d.id = u.id_dependencia
JOIN cat_programas p ON p.id = u.id_programa
JOIN cat_roles r ON r.id = u.id_rol
JOIN cat_semestres s ON s.id = u.id_semestre;

SET FOREIGN_KEY_CHECKS = 1;

