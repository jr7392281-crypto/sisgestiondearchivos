-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-06-2026 a las 17:59:06
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sisgestiondearchivos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_archivos`
--

CREATE TABLE `tb_archivos` (
  `id_archivos` int(11) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `estado_archivo` enum('privado','publico') NOT NULL DEFAULT 'privado',
  `id_carpeta` int(11) UNSIGNED DEFAULT NULL,
  `tipo` varchar(50) NOT NULL,
  `tamaño` int(10) UNSIGNED NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tb_archivos`
--

INSERT INTO `tb_archivos` (`id_archivos`, `nombre`, `estado_archivo`, `id_carpeta`, `tipo`, `tamaño`, `ruta`, `created_at`, `updated_at`) VALUES
(1, '20260611160743__inventario.xlsx', 'publico', 1, 'xlsx', 10438, 'storage/public/1/1/20260611160743__inventario.xlsx', '2026-06-11 21:07:43', '2026-06-12 01:36:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_archivos_compartidos`
--

CREATE TABLE `tb_archivos_compartidos` (
  `id_compartido` int(11) UNSIGNED NOT NULL,
  `id_archivo` int(11) UNSIGNED NOT NULL,
  `id_usuario_origen` int(11) UNSIGNED NOT NULL,
  `id_usuario_destino` int(11) UNSIGNED NOT NULL,
  `permiso` enum('ver','descargar') NOT NULL DEFAULT 'ver',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_carpetas`
--

CREATE TABLE `tb_carpetas` (
  `id_carpeta` int(11) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `carpeta_padre_id` int(11) UNSIGNED DEFAULT NULL,
  `id_usuario` int(11) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tb_carpetas`
--

INSERT INTO `tb_carpetas` (`id_carpeta`, `nombre`, `color`, `carpeta_padre_id`, `id_usuario`, `created_at`, `updated_at`) VALUES
(1, 'documentos de excel', NULL, NULL, 1, '2026-06-11 21:07:18', '2026-06-11 21:07:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_email_verification`
--

CREATE TABLE `tb_email_verification` (
  `id_verificacion` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `token` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_enlaces_compartidos`
--

CREATE TABLE `tb_enlaces_compartidos` (
  `id_enlace` int(11) UNSIGNED NOT NULL,
  `id_archivo` int(11) UNSIGNED NOT NULL,
  `id_usuario_creador` int(11) UNSIGNED NOT NULL,
  `token` varchar(100) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_expiracion` datetime DEFAULT NULL,
  `total_descargas` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_papelera_archivos`
--

CREATE TABLE `tb_papelera_archivos` (
  `id_papelera` int(11) UNSIGNED NOT NULL,
  `id_archivo` int(11) UNSIGNED NOT NULL,
  `id_usuario_elimino` int(11) UNSIGNED NOT NULL,
  `fecha_eliminacion` datetime NOT NULL,
  `fecha_expiracion` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_password_reset`
--

CREATE TABLE `tb_password_reset` (
  `id_reset` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `token` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_permision`
--

CREATE TABLE `tb_permision` (
  `id_permiso` int(11) UNSIGNED NOT NULL,
  `nombre_permiso` varchar(255) NOT NULL,
  `id_rol` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tb_permision`
--

INSERT INTO `tb_permision` (`id_permiso`, `nombre_permiso`, `id_rol`, `created_at`, `updated_at`) VALUES
(1, 'crear_usuario', 1, '2026-02-12 06:00:04', '2026-02-12 06:00:04'),
(2, 'editar_usuario', 1, '2026-02-12 06:00:04', '2026-02-12 06:00:04'),
(3, 'eliminar_usuario', 1, '2026-02-12 06:00:04', '2026-02-12 06:00:04'),
(4, 'subir_archivo', 1, '2026-02-12 06:00:04', '2026-02-12 06:00:04'),
(5, 'eliminar_archivo', 1, '2026-02-12 06:00:04', '2026-02-12 06:00:04'),
(6, 'ver_todos_archivos', 1, '2026-02-12 06:00:04', '2026-02-12 06:00:04'),
(7, 'subir_archivo', 2, '2026-02-12 06:00:04', '2026-02-12 06:00:04'),
(8, 'ver_mis_archivos', 2, '2026-02-12 06:00:04', '2026-02-12 06:00:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_roles`
--

CREATE TABLE `tb_roles` (
  `id_rol` int(11) UNSIGNED NOT NULL,
  `rol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tb_roles`
--

INSERT INTO `tb_roles` (`id_rol`, `rol`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', '2025-11-30 20:35:16', '2025-11-30 20:35:16'),
(2, 'Usuario', '2025-11-30 20:36:14', '2025-11-30 20:36:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_users`
--

CREATE TABLE `tb_users` (
  `id_usuario` int(11) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_user` text NOT NULL,
  `id_rol` int(11) UNSIGNED NOT NULL,
  `email_verificado` tinyint(1) NOT NULL DEFAULT 0,
  `email_verificado_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tb_users`
--

INSERT INTO `tb_users` (`id_usuario`, `nombre`, `email`, `password_user`, `id_rol`, `email_verificado`, `email_verificado_at`, `created_at`, `updated_at`) VALUES
(1, 'Administrador Demo', 'admin@demo.com', '$2y$10$rK147kXTXinuZ0O/q83huugDnS2/LfXx73U6P2hOqUPuR/XL/OWoG', 1, 1, '2026-06-03 13:51:26', '2026-06-03 18:51:26', '2026-06-03 18:51:26');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tb_archivos`
--
ALTER TABLE `tb_archivos`
  ADD PRIMARY KEY (`id_archivos`),
  ADD KEY `carpeta_id` (`id_carpeta`);

--
-- Indices de la tabla `tb_archivos_compartidos`
--
ALTER TABLE `tb_archivos_compartidos`
  ADD PRIMARY KEY (`id_compartido`),
  ADD UNIQUE KEY `uq_archivo_usuario_destino` (`id_archivo`,`id_usuario_destino`),
  ADD KEY `id_usuario_origen` (`id_usuario_origen`),
  ADD KEY `id_usuario_destino` (`id_usuario_destino`);

--
-- Indices de la tabla `tb_carpetas`
--
ALTER TABLE `tb_carpetas`
  ADD PRIMARY KEY (`id_carpeta`),
  ADD KEY `carpeta_padre_id` (`carpeta_padre_id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `tb_email_verification`
--
ALTER TABLE `tb_email_verification`
  ADD PRIMARY KEY (`id_verificacion`),
  ADD UNIQUE KEY `uq_tb_email_verification_token` (`token`),
  ADD UNIQUE KEY `uq_tb_email_verification_user` (`id_usuario`);

--
-- Indices de la tabla `tb_enlaces_compartidos`
--
ALTER TABLE `tb_enlaces_compartidos`
  ADD PRIMARY KEY (`id_enlace`),
  ADD UNIQUE KEY `uq_enlace_token` (`token`),
  ADD KEY `id_archivo` (`id_archivo`),
  ADD KEY `id_usuario_creador` (`id_usuario_creador`);

--
-- Indices de la tabla `tb_papelera_archivos`
--
ALTER TABLE `tb_papelera_archivos`
  ADD PRIMARY KEY (`id_papelera`),
  ADD UNIQUE KEY `uq_papelera_archivo` (`id_archivo`),
  ADD KEY `id_usuario_elimino` (`id_usuario_elimino`);

--
-- Indices de la tabla `tb_password_reset`
--
ALTER TABLE `tb_password_reset`
  ADD PRIMARY KEY (`id_reset`),
  ADD UNIQUE KEY `uq_tb_password_reset_token` (`token`),
  ADD UNIQUE KEY `uq_tb_password_reset_user` (`id_usuario`);

--
-- Indices de la tabla `tb_permision`
--
ALTER TABLE `tb_permision`
  ADD PRIMARY KEY (`id_permiso`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `tb_roles`
--
ALTER TABLE `tb_roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `uq_tb_users_email` (`email`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tb_archivos`
--
ALTER TABLE `tb_archivos`
  MODIFY `id_archivos` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tb_archivos_compartidos`
--
ALTER TABLE `tb_archivos_compartidos`
  MODIFY `id_compartido` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_carpetas`
--
ALTER TABLE `tb_carpetas`
  MODIFY `id_carpeta` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tb_email_verification`
--
ALTER TABLE `tb_email_verification`
  MODIFY `id_verificacion` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_enlaces_compartidos`
--
ALTER TABLE `tb_enlaces_compartidos`
  MODIFY `id_enlace` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_papelera_archivos`
--
ALTER TABLE `tb_papelera_archivos`
  MODIFY `id_papelera` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_password_reset`
--
ALTER TABLE `tb_password_reset`
  MODIFY `id_reset` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tb_permision`
--
ALTER TABLE `tb_permision`
  MODIFY `id_permiso` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tb_roles`
--
ALTER TABLE `tb_roles`
  MODIFY `id_rol` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tb_users`
--
ALTER TABLE `tb_users`
  MODIFY `id_usuario` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tb_archivos`
--
ALTER TABLE `tb_archivos`
  ADD CONSTRAINT `tb_archivos_ibfk_1` FOREIGN KEY (`id_carpeta`) REFERENCES `tb_carpetas` (`id_carpeta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_archivos_compartidos`
--
ALTER TABLE `tb_archivos_compartidos`
  ADD CONSTRAINT `fk_compartidos_archivo` FOREIGN KEY (`id_archivo`) REFERENCES `tb_archivos` (`id_archivos`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_compartidos_usuario_destino` FOREIGN KEY (`id_usuario_destino`) REFERENCES `tb_users` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_compartidos_usuario_origen` FOREIGN KEY (`id_usuario_origen`) REFERENCES `tb_users` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_carpetas`
--
ALTER TABLE `tb_carpetas`
  ADD CONSTRAINT `tb_carpetas_ibfk_1` FOREIGN KEY (`carpeta_padre_id`) REFERENCES `tb_carpetas` (`id_carpeta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_carpetas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `tb_users` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_email_verification`
--
ALTER TABLE `tb_email_verification`
  ADD CONSTRAINT `fk_email_verification_user` FOREIGN KEY (`id_usuario`) REFERENCES `tb_users` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_enlaces_compartidos`
--
ALTER TABLE `tb_enlaces_compartidos`
  ADD CONSTRAINT `fk_enlaces_archivo` FOREIGN KEY (`id_archivo`) REFERENCES `tb_archivos` (`id_archivos`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_enlaces_usuario` FOREIGN KEY (`id_usuario_creador`) REFERENCES `tb_users` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_papelera_archivos`
--
ALTER TABLE `tb_papelera_archivos`
  ADD CONSTRAINT `fk_papelera_archivo` FOREIGN KEY (`id_archivo`) REFERENCES `tb_archivos` (`id_archivos`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_papelera_usuario` FOREIGN KEY (`id_usuario_elimino`) REFERENCES `tb_users` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_password_reset`
--
ALTER TABLE `tb_password_reset`
  ADD CONSTRAINT `tb_password_reset_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `tb_users` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_permision`
--
ALTER TABLE `tb_permision`
  ADD CONSTRAINT `tb_permision_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `tb_roles` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_users`
--
ALTER TABLE `tb_users`
  ADD CONSTRAINT `tb_users_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `tb_roles` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
