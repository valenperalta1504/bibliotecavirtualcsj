-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-08-2023 a las 01:59:35
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `biblioteca_virtual`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `Remitente` varchar(30) NOT NULL,
  `Mensaje` varchar(1000) NOT NULL,
  `Tipo` varchar(30) NOT NULL,
  `Estado` varchar(30) NOT NULL,
  `Destinatario` varchar(30) NOT NULL,
  `Fecha_envío` date NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eliminados`
--

CREATE TABLE `eliminados` (
  `id` int(11) NOT NULL,
  `mensaje` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habilitar_registro`
--

CREATE TABLE `habilitar_registro` (
  `id` int(11) NOT NULL,
  `Estado` varchar(30) NOT NULL,
  `Página` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `habilitar_registro`
--

INSERT INTO `habilitar_registro` (`id`, `Estado`, `Página`) VALUES
(1, 'ocultar', 'registro'),
(2, 'mostrar', 'chat'),
(3, 'mostrar', 'reseñas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_prestamos`
--

CREATE TABLE `historial_prestamos` (
  `id` int(11) NOT NULL,
  `Nombre_alumno` varchar(30) NOT NULL,
  `Dni` int(8) NOT NULL,
  `Nivel` varchar(30) NOT NULL,
  `Curso` varchar(30) NOT NULL,
  `División` varchar(30) NOT NULL,
  `Nombre_libro` varchar(30) NOT NULL,
  `ISBN` varchar(30) NOT NULL,
  `Portada` varchar(255) NOT NULL,
  `Fecha_retiro` date NOT NULL,
  `Fecha_devolución` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id` int(11) NOT NULL,
  `Título` varchar(99) NOT NULL,
  `Autor` varchar(99) NOT NULL,
  `Editorial` varchar(99) NOT NULL,
  `ISBN` varchar(13) NOT NULL,
  `Año de publicación` varchar(12) NOT NULL,
  `Descripción` mediumtext NOT NULL,
  `Categorías` varchar(99) NOT NULL,
  `Número de ejemplares disponibles` int(255) NOT NULL,
  `Stock` int(30) NOT NULL,
  `Portada` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id` int(11) NOT NULL,
  `Nombre_alumno` varchar(30) NOT NULL,
  `Dni` int(8) NOT NULL,
  `Nivel` varchar(30) NOT NULL,
  `Curso` varchar(30) NOT NULL,
  `División` varchar(30) NOT NULL,
  `Nombre_libro` varchar(30) NOT NULL,
  `Portada` varchar(255) NOT NULL,
  `Fecha_retiro` date NOT NULL,
  `Fecha_devolución` date NOT NULL,
  `ISBN` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro`
--

CREATE TABLE `registro` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(30) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `email` varchar(30) NOT NULL,
  `nivel` varchar(30) NOT NULL,
  `dni` int(8) NOT NULL,
  `curso` varchar(30) NOT NULL,
  `division` varchar(30) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `Ban` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro`
--

INSERT INTO `registro` (`id`, `nombre_completo`, `contrasena`, `email`, `nivel`, `dni`, `curso`, `division`, `usuario`, `Ban`) VALUES
(1, 'admin', '$2y$10$Dkv05FeWQpdtf1b97zmoTOTfid25qephxlEkTRxNDo.b2/xOajPLO', '', 'Personal Administrativo', 11111111, '', '', 'admin', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservaciones`
--

CREATE TABLE `reservaciones` (
  `id` int(11) NOT NULL,
  `Dni` int(8) NOT NULL,
  `Nombre_alumno` varchar(30) NOT NULL,
  `Nivel` varchar(30) NOT NULL,
  `Curso` varchar(30) NOT NULL,
  `División` varchar(30) NOT NULL,
  `ISBN` varchar(30) NOT NULL,
  `Nombre_libro` varchar(30) NOT NULL,
  `Portada` varchar(255) NOT NULL,
  `Fecha` date NOT NULL,
  `Estado` varchar(30) NOT NULL,
  `Acción` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reseñas`
--

CREATE TABLE `reseñas` (
  `id` int(11) NOT NULL,
  `ISBN` varchar(13) NOT NULL,
  `Dni` varchar(30) NOT NULL,
  `Nombre_alumno` varchar(30) NOT NULL,
  `Valoración` int(11) NOT NULL,
  `Reseña` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `eliminados`
--
ALTER TABLE `eliminados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `habilitar_registro`
--
ALTER TABLE `habilitar_registro`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_prestamos`
--
ALTER TABLE `historial_prestamos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registro`
--
ALTER TABLE `registro`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reservaciones`
--
ALTER TABLE `reservaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reseñas`
--
ALTER TABLE `reseñas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT de la tabla `eliminados`
--
ALTER TABLE `eliminados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `habilitar_registro`
--
ALTER TABLE `habilitar_registro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `historial_prestamos`
--
ALTER TABLE `historial_prestamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `registro`
--
ALTER TABLE `registro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de la tabla `reservaciones`
--
ALTER TABLE `reservaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `reseñas`
--
ALTER TABLE `reseñas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
