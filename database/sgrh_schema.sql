-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-11-2025 a las 03:43:57
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

Drop DATABASE if EXISTS sgrh;
Create DATABASE sgrh; 
use sgrh;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sgrh`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `estado` enum('pendiente','completa','justificada') DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`id`, `empleado_id`, `fecha`, `hora_entrada`, `hora_salida`, `estado`, `created_at`, `updated_at`) VALUES
(2, 1, '2025-11-15', '17:45:55', '00:00:00', 'justificada', '2025-11-15 23:45:55', '2025-11-16 00:11:38'),
(3, 2, '2025-11-15', '18:15:06', '18:15:27', 'completa', '2025-11-16 00:15:06', '2025-11-16 00:15:27'),
(5, 3, '2025-11-15', '18:28:11', '18:36:20', 'completa', '2025-11-16 00:28:11', '2025-11-16 00:36:20'),
(11, 4, '2025-11-17', '16:14:22', '00:00:00', 'justificada', '2025-11-17 22:14:22', '2025-11-20 19:18:00'),
(12, 2, '2025-11-19', '00:00:00', '00:00:00', 'justificada', '2025-11-18 01:11:23', '2025-11-18 01:11:23'),
(13, 2, '2025-11-17', '19:42:38', '19:42:46', 'completa', '2025-11-18 01:42:38', '2025-11-18 01:42:46'),
(16, 2, '2025-11-18', '12:06:47', '12:06:48', 'completa', '2025-11-18 18:06:47', '2025-11-18 18:06:48'),
(17, 5, '2025-11-19', '14:33:27', NULL, 'justificada', '2025-11-19 20:33:27', '2025-11-19 20:34:01'),
(18, 6, '2025-11-19', '14:36:35', '14:36:41', 'completa', '2025-11-19 20:36:35', '2025-11-19 20:36:41'),
(19, 3, '2025-11-20', '13:13:14', '13:13:20', 'completa', '2025-11-20 19:13:14', '2025-11-20 19:13:20'),
(20, 2, '2025-11-22', '14:23:58', '14:27:02', 'completa', '2025-11-22 20:23:58', '2025-11-22 20:27:02'),
(21, 8, '2025-11-22', '14:35:29', NULL, 'pendiente', '2025-11-22 20:35:29', '2025-11-22 20:35:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Tecnologías de la Información', 'Soporte y desarrollo', '2025-11-15 21:52:32', '2025-11-15 21:52:32'),
(2, 'Recursos Humanos', 'Gestión del personal', '2025-11-15 21:52:32', '2025-11-15 21:52:32'),
(5, 'Base de Datos', 'Analista en base de datos', '2025-11-22 20:04:59', '2025-11-22 20:09:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disponibilidades`
--

CREATE TABLE `disponibilidades` (
  `id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` enum('disponible','ausente','descanso','especial') NOT NULL DEFAULT 'disponible',
  `comentario` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `disponibilidades`
--

INSERT INTO `disponibilidades` (`id`, `empleado_id`, `fecha`, `estado`, `comentario`, `created_at`, `updated_at`) VALUES
(5, 2, '2025-11-20', 'descanso', '.', '2025-11-18 18:09:07', '2025-11-18 18:09:07'),
(6, 3, '2025-11-22', 'descanso', 'Ausente\r\n', '2025-11-20 19:16:47', '2025-11-20 19:16:47'),
(7, 6, '2025-11-25', 'descanso', 'Disponible para turno completo', '2025-11-22 20:15:54', '2025-11-22 20:17:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `user_id`, `nombre`, `apellidos`, `genero`, `correo`, `curp`, `puesto`, `departamento_id`, `estado`, `created_at`, `updated_at`) VALUES
(1, 5, 'Santiago', 'Ponciano', 'masculino', 'sjm345@demo.com', 'SRDO050319MSDSXVBN', 'Diseñadora', 2, 'activo', '2025-11-15 22:55:57', '2025-11-15 22:55:57'),
(2, 6, 'Gissel', 'Moreno', 'femenino', 'jmj@demo.com', 'SOOF020331MMXNASDD', 'Enfermeria', 1, 'activo', '2025-11-16 00:14:03', '2025-11-16 00:14:03'),
(3, 7, 'Cesar Ivan', 'Moreno', 'masculino', 'cm@demo.com', 'SOOF020331MMXNASQW', 'Analista de Base de Datos', 1, 'activo', '2025-11-16 00:27:25', '2025-11-16 00:27:25'),
(4, 11, 'Manuel', 'Rodriguez', 'masculino', 'jmj12@demo.com', 'SOOF020331MMXNAS0E', 'Diseñadora', 1, 'activo', '2025-11-17 22:11:09', '2025-11-17 22:11:09'),
(5, 12, 'Juan', 'Mena', 'otro', 'jm@demo.com', 'SOOF020331MMXNA145', 'Patron', 1, 'activo', '2025-11-19 20:33:01', '2025-11-19 20:33:01'),
(6, 13, 'Gerardó', 'Ortíz', 'otro', 'go@demo.com', 'SRDO050319MSDSX914', 'Chicharronero', 2, 'activo', '2025-11-19 20:36:02', '2025-11-19 20:36:02'),
(8, 17, 'Sofia', 'Romero', 'femenino', 'sr@demo.com', 'SOOF020331MMXN1233', 'Enfermeria', 2, 'activo', '2025-11-22 20:35:10', '2025-11-22 20:35:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `justificantes`
--

CREATE TABLE `justificantes` (
  `id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `archivo` varchar(255) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `justificantes`
--

INSERT INTO `justificantes` (`id`, `empleado_id`, `motivo`, `archivo`, `fecha_inicio`, `fecha_fin`, `estado`, `created_at`, `updated_at`) VALUES
(13, 2, 'Se murio mi perro', 'uploads/justificantes/da72dc4753553460_1763262867.pdf', '2025-11-10', '2025-11-12', 'aprobado', '2025-11-16 03:14:27', '2025-11-16 03:15:24'),
(14, 2, 'Se murio mi perro', 'uploads/justificantes/c1cc47cc4677764f_1763262880.pdf', '2025-11-17', '2025-11-18', 'aprobado', '2025-11-16 03:14:40', '2025-11-16 03:21:04'),
(15, 2, 'Se murio mi perro', 'uploads/justificantes/5fb2d41607d462ad_1763263395.pdf', '2025-11-19', '2025-11-24', 'aprobado', '2025-11-16 03:23:15', '2025-11-16 03:52:34'),
(16, 2, 'Se murio mi perro', 'uploads/justificantes/51326f4e99ec1561_1763266312.pdf', '2025-11-18', '2025-11-20', 'rechazado', '2025-11-16 04:11:52', '2025-11-18 18:25:59'),
(17, 2, 'Se murio mi perro', 'uploads/justificantes/f1bd5a7eee364d98_1763427905.pdf', '2025-11-26', '2025-11-30', 'rechazado', '2025-11-18 01:05:05', '2025-11-18 18:25:57'),
(18, 6, 'Se murio mi conejo', 'uploads/justificantes/0b2ceca8b0f22875_1763584646.pdf', '2025-11-20', '2025-11-23', 'aprobado', '2025-11-19 20:37:26', '2025-11-19 20:38:27'),
(19, 3, 'Se murio mi conejo', 'uploads/justificantes/2d89edd4b26f3a5d_1763666030.pdf', '2025-11-21', '2025-11-22', 'aprobado', '2025-11-20 19:13:50', '2025-11-20 19:15:31'),
(20, 3, 'Se murio mi perro', 'uploads/justificantes/46c06f5605f9adee_1763844145.pdf', '2025-11-24', '2025-11-28', 'aprobado', '2025-11-22 20:42:25', '2025-11-22 20:50:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `tipo` enum('vacaciones','permiso') NOT NULL,
  `motivo` text DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` enum('pendiente','aprobada','rechazada') NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Volcado de datos para la tabla `solicitudes`
--

INSERT INTO `solicitudes` (`id`, `empleado_id`, `tipo`, `motivo`, `fecha_inicio`, `fecha_fin`, `estado`, `created_at`, `updated_at`) VALUES
(1, 2, 'vacaciones', 'vav', '2025-11-25', '2025-12-05', 'aprobada', '2025-11-16 04:48:32', '2025-11-22 02:23:08'),
(2, 2, 'vacaciones', 'Descanso por fin de mes.', '2025-11-25', '2025-12-05', 'aprobada', '2025-11-22 02:47:30', '2025-11-22 02:51:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','rh','empleado') NOT NULL DEFAULT 'empleado',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `empleado_id` int(11) DEFAULT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `active`, `created_at`, `updated_at`, `empleado_id`, `must_change_password`) VALUES
(1, 'Administrador', 'admin@demo.com', '$2y$10$IOAEYbln28LnErd4uAzjLe/NY45ETh9KSvvSrtOFgZiC7HAglEg4W', 'admin', 1, '2025-11-15 21:52:32', '2025-11-15 22:42:24', 0, 1),
(5, 'Santiago Ponciano', 'sjm345@demo.com', '$2y$10$B7ReZnnZcjKjBF10eQpfy.3HdYCb2TYlfrAIa4TJ34TOdwluURHgW', 'empleado', 1, '2025-11-15 22:55:57', '2025-11-16 00:22:05', 0, 1),
(6, 'Gissel Moreno', 'jmj@demo.com', '$2y$10$ztflSd3YYd4p8GHNKGOeceg6M8Ut5LlK4fty/tcu955N/kMgyx9U6', 'empleado', 1, '2025-11-16 00:14:03', '2025-11-17 22:09:25', 0, 1),
(7, 'Cesar Ivan Moreno', 'cm@demo.com', '$2y$10$E5AL3CJzZIeps8ce0S5kPu1SV3sU7DDpYKP7i1T0pjWb8wFyCQhmq', 'empleado', 1, '2025-11-16 00:27:25', '2025-11-16 00:27:25', 0, 1),
(9, 'Sandra Leon', 'sleon@demo.com', '$2y$10$mJC0MgGPpF/c2p.TwfGdTOk5nTCbOypRgZ85w5I/01vpV7BNC5EnC', 'rh', 1, '2025-11-16 00:43:21', '2025-11-16 00:43:21', 0, 1),
(11, 'Manuel Rodriguez', 'jmj12@demo.com', '$2y$10$lbglPx0Nvm5N7VdmiNhHKeTGgAk0ni7jZkEf.zqogm6QuFNKQZH/y', 'empleado', 1, '2025-11-17 22:11:09', '2025-11-17 22:11:09', 0, 1),
(12, 'Juan Mena', 'jm@demo.com', '$2y$10$8wqVZgr4hYkgmeeX6BCp1evmYm.hUVe1CmKU3Jcr5Eyd03SZkwT3G', 'empleado', 1, '2025-11-19 20:33:01', '2025-11-19 20:33:01', NULL, 1),
(13, 'Gerardó Ortíz', 'go@demo.com', '$2y$10$FtKz69rTVS6qHHe4hBY9A.xv3/Gcbk4Cc4DwSl7pQoQJsbnEbmJAC', 'empleado', 1, '2025-11-19 20:36:02', '2025-11-19 20:36:02', NULL, 1),
(14, 'José Juan', 'jmj3@demo.com', '$2y$10$3JcbW387h1WRSQrxfSosMOgpYweFEnumHhLZ7hZjTrf4PsrHhNOf6', 'rh', 0, '2025-11-19 20:39:58', '2025-11-21 23:22:56', NULL, 1),
(15, 'José Enrique Zagalo Solano', 'jz@demo.com', '$2y$10$VVnUGKRFas8DChEovAFRAeU.sqpnRgAZ2IjFD2Vz7/lN60Sdokwai', 'empleado', 1, '2025-11-22 02:12:26', '2025-11-22 02:12:26', NULL, 1),
(16, 'Roberto Enrique López Díaz', 'rlopez@demo.com', '$2y$10$RtPzFfH1QodzzCq0P0q1bOup/93pty04yXx1O7JaI7RhelaKqIasS', 'empleado', 0, '2025-11-22 02:56:50', '2025-11-22 03:00:54', NULL, 1),
(17, 'Sofia Romero', 'sr@demo.com', '$2y$10$TOWnUf1XLVj.WriFPG3CXOX.Rm7/rseA2iXXIzcDLdSQscjwOxDfq', 'empleado', 1, '2025-11-22 20:35:10', '2025-11-22 20:35:10', NULL, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_asistencia_fecha` (`empleado_id`,`fecha`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `disponibilidades`
--
ALTER TABLE `disponibilidades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_disponibilidad_fecha` (`empleado_id`,`fecha`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `curp` (`curp`),
  ADD KEY `fk_empleado_departamento` (`departamento_id`);

--
-- Indices de la tabla `justificantes`
--
ALTER TABLE `justificantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_justificante_empleado` (`empleado_id`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_solicitud_empleado` (`empleado_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `disponibilidades`
--
ALTER TABLE `disponibilidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `justificantes`
--
ALTER TABLE `justificantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `fk_asistencia_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `disponibilidades`
--
ALTER TABLE `disponibilidades`
  ADD CONSTRAINT `fk_disponibilidad_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `fk_empleado_departamento` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `justificantes`
--
ALTER TABLE `justificantes`
  ADD CONSTRAINT `fk_justificante_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `fk_solicitud_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
