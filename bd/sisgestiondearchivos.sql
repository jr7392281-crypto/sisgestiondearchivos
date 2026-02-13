-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-02-2026 a las 00:44:42
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
  `id_carpeta` int(11) UNSIGNED DEFAULT NULL,
  `tipo` varchar(50) NOT NULL,
  `tamaño` int(10) UNSIGNED NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tb_archivos`
--

INSERT INTO `tb_archivos` (`id_archivos`, `nombre`, `id_carpeta`, `tipo`, `tamaño`, `ruta`, `created_at`, `updated_at`) VALUES
(9, '20260210175549__tecnohogar.docx', 7, '', 0, '', '2026-02-10 22:55:49', '2026-02-10 22:55:49'),
(10, '20260213170743__Gran Chocolatada.png', 1, '', 0, '', '2026-02-13 22:07:43', '2026-02-13 22:07:43'),
(11, '20260213170811__Picsart_24-09-02_17-52-54-055.jpg', 1, '', 0, '', '2026-02-13 22:08:11', '2026-02-13 22:08:11'),
(12, '20260213172828__Informe-Modulo2.pdf', 1, '', 0, '', '2026-02-13 22:28:28', '2026-02-13 22:28:28'),
(13, '20260213172837__MODUL0 2 FINAL.docx', 1, '', 0, '', '2026-02-13 22:28:37', '2026-02-13 22:28:37');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tb_carpetas`
--

INSERT INTO `tb_carpetas` (`id_carpeta`, `nombre`, `color`, `carpeta_padre_id`, `id_usuario`, `created_at`, `updated_at`) VALUES
(1, '2020', 'yellow', NULL, 3, '2025-12-01 02:18:16', '2025-12-01 02:18:16'),
(2, '2021', 'green', NULL, 3, '2025-12-01 02:18:23', '2025-12-01 02:18:23'),
(3, 'Documentos Generales', 'black', NULL, 3, '2025-12-01 02:27:07', '2025-12-01 02:27:07'),
(4, 'Recursos Humanos', 'green', NULL, 3, '2025-12-01 02:27:07', '2025-12-01 02:27:07'),
(5, 'Logística', 'green', NULL, 3, '2025-12-01 02:27:07', '2025-12-01 02:27:07'),
(6, 'Informes', 'yellow', NULL, 3, '2025-12-01 02:27:07', '2025-12-01 02:27:07'),
(7, 'Tesorería', 'black', NULL, 3, '2025-12-01 02:27:07', '2025-12-01 02:27:07'),
(8, 'Archivo Histórico', 'red', NULL, 3, '2025-12-01 02:27:07', '2025-12-01 02:27:07'),
(9, 'Trámite Documentario', 'blue', NULL, 3, '2025-12-01 02:27:07', '2025-12-01 02:27:07'),
(10, 'Proyectos y Obras', 'blue', NULL, 3, '2025-12-01 02:27:07', '2025-12-01 02:27:07'),
(11, 'Contratos', NULL, 2, 3, '2025-12-01 02:29:54', '2025-12-01 02:29:54'),
(12, 'Asistencias', NULL, 2, 3, '2025-12-01 02:29:54', '2025-12-01 02:29:54'),
(13, 'Memorandos', NULL, 2, 3, '2025-12-01 02:29:54', '2025-12-01 02:29:54'),
(14, 'Personal Cesado', NULL, 2, 3, '2025-12-01 02:29:54', '2025-12-01 02:29:54'),
(15, 'cvbc', NULL, 10, NULL, '2026-02-10 22:13:15', '2026-02-10 22:13:15'),
(16, 'bbn', NULL, 5, NULL, '2026-02-10 22:13:35', '2026-02-10 22:13:35'),
(17, 'd', NULL, 5, NULL, '2026-02-10 22:13:44', '2026-02-10 22:13:44');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tb_users`
--

INSERT INTO `tb_users` (`id_usuario`, `nombre`, `email`, `password_user`, `id_rol`, `created_at`, `updated_at`) VALUES
(1, 'Carlos', 'carlos@gmail.com', '1234', 1, '2025-11-30 20:41:25', '2025-11-30 20:41:25'),
(2, 'Raul', 'Raul@gmail.com', '1234', 2, '2025-11-30 20:42:13', '2025-11-30 20:42:13'),
(3, 'jose', 'jose@gmail.com', '$2y$10$m/pNiS8E/qANdGDpz999sOabjm2oGHZDyyK2JubW0Bz4GIJ88yVDy', 1, '2025-12-01 02:14:24', '2025-12-01 02:17:12'),
(4, 'josue', 'jos@gmail.com', '$2y$10$CyRZxQdDd7d8/NSJsz6J4eqY7q6wPuo.SUTRAbLtGFReBWzaEyCsK', 2, '2026-02-11 23:19:39', '2026-02-11 23:19:39');

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
-- Indices de la tabla `tb_carpetas`
--
ALTER TABLE `tb_carpetas`
  ADD PRIMARY KEY (`id_carpeta`),
  ADD KEY `carpeta_padre_id` (`carpeta_padre_id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `tb_password_reset`
--
ALTER TABLE `tb_password_reset`
  ADD PRIMARY KEY (`id_reset`),
  ADD KEY `id_usuario` (`id_usuario`);

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
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tb_archivos`
--
ALTER TABLE `tb_archivos`
  MODIFY `id_archivos` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `tb_carpetas`
--
ALTER TABLE `tb_carpetas`
  MODIFY `id_carpeta` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
  MODIFY `id_usuario` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tb_archivos`
--
ALTER TABLE `tb_archivos`
  ADD CONSTRAINT `tb_archivos_ibfk_1` FOREIGN KEY (`id_carpeta`) REFERENCES `tb_carpetas` (`id_carpeta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tb_carpetas`
--
ALTER TABLE `tb_carpetas`
  ADD CONSTRAINT `tb_carpetas_ibfk_1` FOREIGN KEY (`carpeta_padre_id`) REFERENCES `tb_carpetas` (`id_carpeta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_carpetas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `tb_users` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

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
