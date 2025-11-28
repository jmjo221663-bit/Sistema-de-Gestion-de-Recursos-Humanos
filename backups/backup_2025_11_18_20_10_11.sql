-- Respaldo del SGRH
-- Fecha: 2025-11-18 20:10:11

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `asistencias`;
CREATE TABLE `asistencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empleado_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `estado` enum('pendiente','completa','justificada') DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_asistencia_fecha` (`empleado_id`,`fecha`),
  CONSTRAINT `fk_asistencia_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `asistencias` VALUES('2','1','2025-11-15','17:45:55','','justificada','2025-11-15 17:45:55','2025-11-15 18:11:38');
INSERT INTO `asistencias` VALUES('3','2','2025-11-15','18:15:06','18:15:27','completa','2025-11-15 18:15:06','2025-11-15 18:15:27');
INSERT INTO `asistencias` VALUES('5','3','2025-11-15','18:28:11','18:36:20','completa','2025-11-15 18:28:11','2025-11-15 18:36:20');
INSERT INTO `asistencias` VALUES('11','4','2025-11-17','16:14:22','','pendiente','2025-11-17 16:14:22','2025-11-17 16:14:22');
INSERT INTO `asistencias` VALUES('12','2','2025-11-19','','','justificada','2025-11-17 19:11:23','2025-11-17 19:11:23');
INSERT INTO `asistencias` VALUES('13','2','2025-11-17','19:42:38','19:42:46','completa','2025-11-17 19:42:38','2025-11-17 19:42:46');
INSERT INTO `asistencias` VALUES('16','2','2025-11-18','12:06:47','12:06:48','completa','2025-11-18 12:06:47','2025-11-18 12:06:48');

DROP TABLE IF EXISTS `departamentos`;
CREATE TABLE `departamentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `departamentos` VALUES('1','Tecnologías de la Información','Soporte y desarrollo','2025-11-15 15:52:32','2025-11-15 15:52:32');
INSERT INTO `departamentos` VALUES('2','Recursos Humanos','Gestión del personal','2025-11-15 15:52:32','2025-11-15 15:52:32');

DROP TABLE IF EXISTS `disponibilidades`;
CREATE TABLE `disponibilidades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empleado_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` enum('disponible','ausente','descanso','especial') NOT NULL DEFAULT 'disponible',
  `comentario` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_disponibilidad_fecha` (`empleado_id`,`fecha`),
  CONSTRAINT `fk_disponibilidad_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `disponibilidades` VALUES('5','2','2025-11-20','descanso','.','2025-11-18 12:09:07','2025-11-18 12:09:07');

DROP TABLE IF EXISTS `empleados`;
CREATE TABLE `empleados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `genero` enum('masculino','femenino','otro') NOT NULL,
  `correo` varchar(150) NOT NULL,
  `curp` char(18) NOT NULL,
  `puesto` varchar(100) DEFAULT NULL,
  `departamento_id` int(11) NOT NULL,
  `estado` enum('activo','baja') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`),
  UNIQUE KEY `curp` (`curp`),
  KEY `fk_empleado_departamento` (`departamento_id`),
  CONSTRAINT `fk_empleado_departamento` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `empleados` VALUES('1','5','Santiago','Ponciano','masculino','sjm345@demo.com','SRDO050319MSDSXVBN','Diseñadora','2','activo','2025-11-15 16:55:57','2025-11-15 16:55:57');
INSERT INTO `empleados` VALUES('2','6','Gissel','Moreno','femenino','jmj@demo.com','SOOF020331MMXNASDD','Enfermeria','1','activo','2025-11-15 18:14:03','2025-11-15 18:14:03');
INSERT INTO `empleados` VALUES('3','7','Cesar Ivan','Moreno','masculino','cm@demo.com','SOOF020331MMXNASQW','Analista de Base de Datos','1','activo','2025-11-15 18:27:25','2025-11-15 18:27:25');
INSERT INTO `empleados` VALUES('4','11','Manuel','Rodriguez','masculino','jmj12@demo.com','SOOF020331MMXNAS0E','Diseñadora','1','activo','2025-11-17 16:11:09','2025-11-17 16:11:09');

DROP TABLE IF EXISTS `justificantes`;
CREATE TABLE `justificantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empleado_id` int(11) NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `archivo` varchar(255) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_justificante_empleado` (`empleado_id`),
  CONSTRAINT `fk_justificante_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `justificantes` VALUES('13','2','Se murio mi perro','uploads/justificantes/da72dc4753553460_1763262867.pdf','2025-11-10','2025-11-12','aprobado','2025-11-15 21:14:27','2025-11-15 21:15:24');
INSERT INTO `justificantes` VALUES('14','2','Se murio mi perro','uploads/justificantes/c1cc47cc4677764f_1763262880.pdf','2025-11-17','2025-11-18','aprobado','2025-11-15 21:14:40','2025-11-15 21:21:04');
INSERT INTO `justificantes` VALUES('15','2','Se murio mi perro','uploads/justificantes/5fb2d41607d462ad_1763263395.pdf','2025-11-19','2025-11-24','aprobado','2025-11-15 21:23:15','2025-11-15 21:52:34');
INSERT INTO `justificantes` VALUES('16','2','Se murio mi perro','uploads/justificantes/51326f4e99ec1561_1763266312.pdf','2025-11-18','2025-11-20','rechazado','2025-11-15 22:11:52','2025-11-18 12:25:59');
INSERT INTO `justificantes` VALUES('17','2','Se murio mi perro','uploads/justificantes/f1bd5a7eee364d98_1763427905.pdf','2025-11-26','2025-11-30','rechazado','2025-11-17 19:05:05','2025-11-18 12:25:57');

DROP TABLE IF EXISTS `solicitudes`;
CREATE TABLE `solicitudes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empleado_id` int(11) NOT NULL,
  `tipo` enum('vacaciones','permiso') NOT NULL,
  `motivo` text DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` enum('pendiente','aprobada','rechazada') NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_solicitud_empleado` (`empleado_id`),
  CONSTRAINT `fk_solicitud_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chk_fechas` CHECK (`fecha_fin` >= `fecha_inicio`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `solicitudes` VALUES('1','2','vacaciones','vav','2025-11-25','2025-12-05','aprobada','2025-11-15 22:48:32','2025-11-15 22:48:41');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','rh','empleado') NOT NULL DEFAULT 'empleado',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `empleado_id` int(11) DEFAULT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` VALUES('1','Administrador','admin@demo.com','$2y$10$IOAEYbln28LnErd4uAzjLe/NY45ETh9KSvvSrtOFgZiC7HAglEg4W','admin','1','2025-11-15 15:52:32','2025-11-15 16:42:24','','1');
INSERT INTO `users` VALUES('5','Santiago Ponciano','sjm345@demo.com','$2y$10$B7ReZnnZcjKjBF10eQpfy.3HdYCb2TYlfrAIa4TJ34TOdwluURHgW','empleado','1','2025-11-15 16:55:57','2025-11-15 18:22:05','','1');
INSERT INTO `users` VALUES('6','Gissel Moreno','jmj@demo.com','$2y$10$ztflSd3YYd4p8GHNKGOeceg6M8Ut5LlK4fty/tcu955N/kMgyx9U6','empleado','1','2025-11-15 18:14:03','2025-11-17 16:09:25','','1');
INSERT INTO `users` VALUES('7','Cesar Ivan Moreno','cm@demo.com','$2y$10$E5AL3CJzZIeps8ce0S5kPu1SV3sU7DDpYKP7i1T0pjWb8wFyCQhmq','empleado','1','2025-11-15 18:27:25','2025-11-15 18:27:25','','1');
INSERT INTO `users` VALUES('9','Sandra Leon','sleon@demo.com','$2y$10$mJC0MgGPpF/c2p.TwfGdTOk5nTCbOypRgZ85w5I/01vpV7BNC5EnC','rh','1','2025-11-15 18:43:21','2025-11-15 18:43:21','','1');
INSERT INTO `users` VALUES('11','Manuel Rodriguez','jmj12@demo.com','$2y$10$lbglPx0Nvm5N7VdmiNhHKeTGgAk0ni7jZkEf.zqogm6QuFNKQZH/y','empleado','1','2025-11-17 16:11:09','2025-11-17 16:11:09','','1');

SET FOREIGN_KEY_CHECKS=1;
