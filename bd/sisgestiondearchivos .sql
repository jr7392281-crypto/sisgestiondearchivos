-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-04-2026 a las 01:15:33
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
(2, '20260407115719__Ellison, Harlan - No tengo boca y debo gritar.pdf', 'publico', 2, 'pdf', 62923, 'storage/public/3/2/20260407115719__Ellison, Harlan - No tengo boca y debo gritar.pdf', '2026-04-07 16:57:19', '2026-04-20 16:05:19'),
(3, '20260415110007__1 (1).webp', 'publico', 2, 'webp', 30320, 'storage/public/3/2/20260415110007__1 (1).webp', '2026-04-15 16:00:07', '2026-04-16 16:47:10'),
(4, '20260415110228__curriculum vitae.pdf', 'privado', 5, 'pdf', 613219, 'private/6/5/20260415110228__curriculum vitae.pdf', '2026-04-15 16:02:28', '2026-04-15 16:02:28'),
(5, '20260415110244__diagrama flujo proyecto.docx', 'publico', 5, 'docx', 625889, 'storage/public/6/5/20260415110244__diagrama flujo proyecto.docx', '2026-04-15 16:02:44', '2026-04-15 17:04:15'),
(6, '20260415120507__MGMT - Time to Pretend (Official HD Video).mp3', 'privado', 5, 'mp3', 4249691, 'private/6/5/20260415120507__MGMT - Time to Pretend (Official HD Video).mp3', '2026-04-15 17:05:07', '2026-04-15 17:05:07'),
(7, '20260415120721__kurffew - lazy (haste) (official audio).mp3', 'privado', 5, 'mp3', 3004427, 'private/6/5/20260415120721__kurffew - lazy (haste) (official audio).mp3', '2026-04-15 17:07:21', '2026-04-15 17:07:21'),
(8, '20260415120751__oh lord.mp4', 'privado', 5, 'mp4', 6689159, 'private/6/5/20260415120751__oh lord.mp4', '2026-04-15 17:07:51', '2026-04-15 17:07:51'),
(9, '20260415120825__1 (1).webp', 'privado', 5, 'webp', 30320, 'private/6/5/20260415120825__1 (1).webp', '2026-04-15 17:08:25', '2026-04-15 17:08:25'),
(10, '20260416114929__Better Off Alone.mp3', 'privado', 2, 'mp3', 5245101, 'private/3/2/20260416114929__Better Off Alone.mp3', '2026-04-16 16:49:29', '2026-04-16 16:49:29'),
(11, '20260420101516__analisis de competencia.pptx', 'privado', 2, 'pptx', 835856, 'private/3/2/20260420101516__analisis de competencia.pptx', '2026-04-20 15:15:16', '2026-04-20 15:15:16'),
(12, '20260420101516__inventario.xlsx', 'privado', 2, 'xlsx', 10438, 'private/3/2/20260420101516__inventario.xlsx', '2026-04-20 15:15:16', '2026-04-20 15:15:16'),
(13, '20260420101516__logo.jpg', 'privado', 2, 'jpg', 487268, 'private/3/2/20260420101516__logo.jpg', '2026-04-20 15:15:16', '2026-04-20 15:15:16'),
(14, '20260420101542__dov.docx', 'privado', 2, 'docx', 22129, 'private/3/2/20260420101542__dov.docx', '2026-04-20 15:15:42', '2026-04-20 15:15:42'),
(15, '20260424115105__WhatsApp Image 2026-04-17 at 8.35.15 PM.jpeg', 'privado', 2, 'jpeg', 38678, 'private/3/2/20260424115105__WhatsApp Image 2026-04-17 at 8.35.15 PM.jpeg', '2026-04-24 16:51:05', '2026-04-24 16:51:05'),
(16, '20260424115105__WhatsApp Image 2026-04-16 at 10.44.18 AM.jpeg', 'privado', 2, 'jpeg', 127212, 'private/3/2/20260424115105__WhatsApp Image 2026-04-16 at 10.44.18 AM.jpeg', '2026-04-24 16:51:05', '2026-04-24 16:51:05'),
(17, '20260424115105__WhatsApp Image 2026-04-16 at 10.30.34 AM.jpeg', 'privado', 2, 'jpeg', 318723, 'private/3/2/20260424115105__WhatsApp Image 2026-04-16 at 10.30.34 AM.jpeg', '2026-04-24 16:51:05', '2026-04-24 16:51:05'),
(18, '20260424115105__WhatsApp Image 2026-04-16 at 10.34.23 AM.jpeg', 'privado', 2, 'jpeg', 83816, 'private/3/2/20260424115105__WhatsApp Image 2026-04-16 at 10.34.23 AM.jpeg', '2026-04-24 16:51:05', '2026-04-24 16:51:05'),
(19, '20260424115105__WhatsApp Image 2026-04-16 at 10.44.30 AM (1).jpeg', 'privado', 2, 'jpeg', 265552, 'private/3/2/20260424115105__WhatsApp Image 2026-04-16 at 10.44.30 AM (1).jpeg', '2026-04-24 16:51:05', '2026-04-24 16:51:05'),
(20, '20260424115105__WhatsApp Image 2026-04-16 at 10.44.30 AM.jpeg', 'privado', 2, 'jpeg', 265552, 'private/3/2/20260424115105__WhatsApp Image 2026-04-16 at 10.44.30 AM.jpeg', '2026-04-24 16:51:05', '2026-04-24 16:51:05');

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
(2, 'documentos de ingenieria', 'green', NULL, 3, '2026-04-06 16:02:40', '2026-04-06 16:02:40'),
(3, 'cartas', NULL, 2, 3, '2026-04-06 17:39:35', '2026-04-06 17:39:35'),
(4, 'documentos de excel', NULL, 3, 3, '2026-04-06 17:39:56', '2026-04-06 17:39:56'),
(5, 'cartas', NULL, NULL, 6, '2026-04-15 16:02:05', '2026-04-15 16:02:05'),
(6, 'cartas', NULL, NULL, 3, '2026-04-24 16:51:41', '2026-04-24 16:51:41'),
(7, 'documentos de excel', NULL, 2, 3, '2026-04-24 16:51:56', '2026-04-24 16:51:56');

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
-- Estructura de tabla para la tabla `tb_password_reset`
--

CREATE TABLE `tb_password_reset` (
  `id_reset` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `token` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tb_password_reset`
--

INSERT INTO `tb_password_reset` (`id_reset`, `id_usuario`, `token`, `created_at`) VALUES
(14, 8, '0dd791c1fc46616e1375ce1b7f28439f170329afab12ffabe0af57b7df8eaf36', '2026-04-24 14:53:00');

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
(3, 'jose', 'jose@gmail.com', '$2y$10$m/pNiS8E/qANdGDpz999sOabjm2oGHZDyyK2JubW0Bz4GIJ88yVDy', 1, 1, '2026-04-23 10:26:42', '2025-12-01 02:14:24', '2025-12-01 02:17:12'),
(6, 'johnny', 'johnny@gmail.com', '$2y$10$TSwd2cB6PzwyZFma66rHN.8KNFAdLD4ojSkhvi5x8gW5jL8XGazMq', 2, 1, '2026-04-23 10:26:42', '2026-03-10 00:52:02', '2026-03-10 00:52:02'),
(8, 'jose', 'jr7392281@gmail.com', '$2y$10$BIl/KOC3ngJhKRpYdZJUSOpDiE6818kDSjTWBQ7UcajtLWZhjw/Fu', 1, 1, '2026-04-23 15:06:15', '2026-04-23 20:02:47', '2026-04-23 22:19:43'),
(9, 'jose', 'jrr81532@gmail.com', '$2y$10$eTCyL3qpBcTbfgcCYTX3kehsvlTDmTP1o9FJHviBtmNfwIpmDIi2S', 1, 1, '2026-04-23 15:20:13', '2026-04-23 20:14:51', '2026-04-23 20:20:13');

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
-- Indices de la tabla `tb_email_verification`
--
ALTER TABLE `tb_email_verification`
  ADD PRIMARY KEY (`id_verificacion`),
  ADD UNIQUE KEY `uq_tb_email_verification_token` (`token`),
  ADD UNIQUE KEY `uq_tb_email_verification_user` (`id_usuario`);

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
  MODIFY `id_archivos` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `tb_carpetas`
--
ALTER TABLE `tb_carpetas`
  MODIFY `id_carpeta` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tb_email_verification`
--
ALTER TABLE `tb_email_verification`
  MODIFY `id_verificacion` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `tb_password_reset`
--
ALTER TABLE `tb_password_reset`
  MODIFY `id_reset` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  MODIFY `id_usuario` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- Filtros para la tabla `tb_email_verification`
--
ALTER TABLE `tb_email_verification`
  ADD CONSTRAINT `fk_email_verification_user` FOREIGN KEY (`id_usuario`) REFERENCES `tb_users` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

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
