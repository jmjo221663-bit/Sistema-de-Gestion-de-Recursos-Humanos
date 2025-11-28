-- ==========================================================
--  BASE DE DATOS: Sistema de Gestión de Recursos Humanos
--  Versión completa hasta FN7 (Asistencias)
-- ==========================================================
Drop DATABASE if EXISTS sgrh;
CREATE DATABASE IF NOT EXISTS sgrh CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sgrh;

-- ==========================================================
--  Tabla: users
-- ==========================================================
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','rh','empleado') NOT NULL DEFAULT 'empleado',
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- ==========================================================
--  Tabla: departamentos
-- ==========================================================
CREATE TABLE IF NOT EXISTS departamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  descripcion TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ==========================================================
--  Tabla: empleados
-- ==========================================================
CREATE TABLE IF NOT EXISTS empleados (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  apellidos VARCHAR(150) NOT NULL,
  correo VARCHAR(150) NOT NULL UNIQUE,
  curp CHAR(18) NOT NULL UNIQUE,
  puesto VARCHAR(100) NULL,
  departamento_id INT NOT NULL,
  estado ENUM('activo','baja') NOT NULL DEFAULT 'activo',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_empleado_departamento
    FOREIGN KEY (departamento_id)
    REFERENCES departamentos(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB;

ALTER TABLE empleados
ADD genero ENUM('masculino','femenino','otro') NOT NULL AFTER apellidos;

ALTER TABLE users 
ADD empleado_id INT NULL;





-- ==========================================================
--  Tabla: solicitudes (FN3)
-- ==========================================================
CREATE TABLE IF NOT EXISTS solicitudes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  empleado_id INT NOT NULL,
  tipo ENUM('vacaciones','permiso') NOT NULL,
  motivo TEXT NULL,
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE NOT NULL,
  estado ENUM('pendiente','aprobada','rechazada') NOT NULL DEFAULT 'pendiente',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_solicitud_empleado
    FOREIGN KEY (empleado_id)
    REFERENCES empleados(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT chk_fechas CHECK (fecha_fin >= fecha_inicio)
) ENGINE=InnoDB;

-- ==========================================================
--  Tabla: disponibilidades (FN6)
-- ==========================================================
CREATE TABLE IF NOT EXISTS disponibilidades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  empleado_id INT NOT NULL,
  fecha DATE NOT NULL,
  estado ENUM('disponible','ausente','descanso','especial') NOT NULL DEFAULT 'disponible',
  comentario VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_disponibilidad_empleado
    FOREIGN KEY (empleado_id)
    REFERENCES empleados(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT uq_disponibilidad_fecha UNIQUE (empleado_id, fecha)
) ENGINE=InnoDB;

-- ==========================================================
--  Tabla: asistencias (FN7)
-- ==========================================================
CREATE TABLE IF NOT EXISTS asistencias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  empleado_id INT NOT NULL,
  fecha DATE NOT NULL,
  hora_entrada TIME DEFAULT NULL,
  hora_salida TIME DEFAULT NULL,
  estado ENUM('pendiente','completa','justificada') DEFAULT 'pendiente',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_asistencia_empleado
    FOREIGN KEY (empleado_id)
    REFERENCES empleados(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT uq_asistencia_fecha UNIQUE (empleado_id, fecha)
) ENGINE=InnoDB;

-- ==========================================================
--  Tabla: Justificante (FN8)
-- ==========================================================

CREATE TABLE IF NOT EXISTS justificantes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  empleado_id INT NOT NULL,
  motivo VARCHAR(255) NOT NULL,
  archivo VARCHAR(255) NOT NULL,
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE NOT NULL,
  estado ENUM('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_justificante_empleado
    FOREIGN KEY (empleado_id) REFERENCES empleados(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;


-- ==========================================================
--  Inserción de datos iniciales
-- ==========================================================
INSERT INTO departamentos (nombre, descripcion) VALUES
  ('Tecnologías de la Información', 'Soporte y desarrollo'),
  ('Recursos Humanos', 'Gestión del personal')
ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion);

-- Usuario administrador (contraseña: Admin123*)
INSERT INTO users (name, email, password_hash, role, active)
VALUES (
  'Administrador',
  'admin@demo.com',
  '$2y$10$L2Xxr0Rx/eh8IdtfDRzaLO/61mcs4rRjM3OQbIfs4UUFQEl7g/vfW',
  'admin',
  1
)
ON DUPLICATE KEY UPDATE name='Administrador', role='admin', active=1;

-- ==========================================================
--  FIN DE LA BASE DE DATOS (FN1 - FN7)
-- ==========================================================
ALTER TABLE users 
ADD must_change_password TINYINT(1) NOT NULL DEFAULT 1;

ALTER TABLE asistencias 
ADD estado ENUM('asistencia','falta','retardo','justificado') 
DEFAULT 'asistencia';

