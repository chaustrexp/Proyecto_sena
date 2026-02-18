-- Base de datos Dashboard SENA
CREATE DATABASE IF NOT EXISTS dashboard_sena CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dashboard_sena;

-- Tabla Centro de Formación
CREATE TABLE centro_formacion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    direccion VARCHAR(255),
    telefono VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla Sede
CREATE TABLE sede (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL,
    direccion VARCHAR(255),
    ciudad VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla Coordinación
CREATE TABLE coordinacion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL,
    centro_formacion_id INT,
    responsable VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (centro_formacion_id) REFERENCES centro_formacion(id) ON DELETE SET NULL
);

-- Tabla Título Programa
CREATE TABLE titulo_programa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL,
    nivel VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla Programa
CREATE TABLE programa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    duracion_meses INT,
    titulo_programa_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (titulo_programa_id) REFERENCES titulo_programa(id) ON DELETE SET NULL
);

-- Tabla Ficha
CREATE TABLE ficha (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero VARCHAR(50) UNIQUE NOT NULL,
    programa_id INT,
    fecha_inicio DATE,
    fecha_fin DATE,
    estado VARCHAR(50) DEFAULT 'Activa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (programa_id) REFERENCES programa(id) ON DELETE CASCADE
);

-- Tabla Instructor
CREATE TABLE instructor (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL,
    documento VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100),
    telefono VARCHAR(20),
    centro_formacion_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (centro_formacion_id) REFERENCES centro_formacion(id) ON DELETE SET NULL
);

-- Tabla Ambiente
CREATE TABLE ambiente (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    capacidad INT,
    tipo VARCHAR(100),
    sede_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sede_id) REFERENCES sede(id) ON DELETE SET NULL
);

-- Tabla Competencia
CREATE TABLE competencia (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla Competencia_Programa (Relación N:M)
CREATE TABLE competencia_programa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    competencia_id INT,
    programa_id INT,
    horas INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (competencia_id) REFERENCES competencia(id) ON DELETE CASCADE,
    FOREIGN KEY (programa_id) REFERENCES programa(id) ON DELETE CASCADE,
    UNIQUE KEY unique_competencia_programa (competencia_id, programa_id)
);

-- Tabla Asignación
CREATE TABLE asignacion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ficha_id INT,
    instructor_id INT,
    ambiente_id INT,
    competencia_id INT,
    fecha_inicio DATE,
    fecha_fin DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ficha_id) REFERENCES ficha(id) ON DELETE CASCADE,
    FOREIGN KEY (instructor_id) REFERENCES instructor(id) ON DELETE CASCADE,
    FOREIGN KEY (ambiente_id) REFERENCES ambiente(id) ON DELETE SET NULL,
    FOREIGN KEY (competencia_id) REFERENCES competencia(id) ON DELETE SET NULL
);

-- Tabla Detalle_Asignación
CREATE TABLE detalle_asignacion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    asignacion_id INT,
    fecha DATE,
    hora_inicio TIME,
    hora_fin TIME,
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (asignacion_id) REFERENCES asignacion(id) ON DELETE CASCADE
);

-- Datos de ejemplo
INSERT INTO centro_formacion (nombre, codigo, direccion, telefono) VALUES
('Centro de Gestión Administrativa', 'CGA001', 'Calle 52 #13-65', '3001234567'),
('Centro de Tecnología', 'CTE001', 'Carrera 30 #17-00', '3007654321');

INSERT INTO sede (nombre, direccion, ciudad) VALUES
('Sede Principal', 'Calle 52 #13-65', 'Bogotá'),
('Sede Norte', 'Carrera 45 #100-00', 'Bogotá');

INSERT INTO titulo_programa (nombre, nivel) VALUES
('Técnico', 'Técnico'),
('Tecnólogo', 'Tecnológico'),
('Especialización', 'Especialización');

INSERT INTO programa (nombre, codigo, duracion_meses, titulo_programa_id) VALUES
('Análisis y Desarrollo de Software', 'ADSO', 24, 2),
('Gestión Administrativa', 'GA', 18, 1);

INSERT INTO instructor (nombre, documento, email, telefono, centro_formacion_id) VALUES
('Juan Pérez', '1234567890', 'juan.perez@sena.edu.co', '3001111111', 1),
('María García', '0987654321', 'maria.garcia@sena.edu.co', '3002222222', 2);

INSERT INTO ambiente (nombre, codigo, capacidad, tipo, sede_id) VALUES
('Ambiente 101', 'AMB101', 30, 'Aula Teórica', 1),
('Laboratorio 201', 'LAB201', 25, 'Laboratorio', 1);

INSERT INTO competencia (codigo, nombre, descripcion) VALUES
('220501046', 'Programar software', 'Desarrollar aplicaciones según requerimientos'),
('210601001', 'Gestión documental', 'Administrar documentos empresariales');
