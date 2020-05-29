-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-05-2020 a las 20:58:19
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `podcasts`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `audio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_publicacion` datetime NOT NULL,
  `descripcion` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `titulo`, `foto`, `audio`, `fecha_publicacion`, `descripcion`) VALUES
(4, 1, '\'Ídolos\': David Bowie y las canciones del extraterrestre', '1-5ecff4a16cdb7.png', '1-5ecff4a16e72e.mpga', '2020-05-28 19:28:01', 'En este cuento, Anni B Sweet pone voz al valor del artista para defender lo diferente y adaptarse a quien cada uno es en realidad'),
(5, 1, '\'Ídolos\': Freddie Mercury y el increíble viaje del niño de las maletas', '2-5ecff52bb8f1d.png', '2-5ecff52bbaccc.mpga', '2020-05-28 19:30:19', 'Zahara es la narradora de este cuento sobre un inmigrante que huyó de su país y luchó por sus sueños para ser la mayor estrella mundial'),
(6, 1, '\'Ídolos\': Elvis, el niño que se convirtió en Rey', '3-5ecff5da3650c.png', '3-5ecff5da37e7a.mpga', '2020-05-28 19:33:14', 'El músico Jimmy Barnatán pone voz a este cuento sobre el coraje de luchar por los sueños'),
(7, 1, '\'Ídolos\': Patti Smith y el hada de los sueños', '4-5ecff6c14c41c.png', '4-5ecff6c14dc52.mpga', '2020-05-28 19:37:05', 'Eva Amaral nos descubre la vida de la artista, en la que los sueños y la amistad fueron lo más importante'),
(8, 1, '\'Ídolos\': Amy Winehouse y los fantasmas', '5-5ecff74702325.png', '5-5ecff74703af9.mpga', '2020-05-28 19:39:18', 'Guille Galván (Vetusta Morla) pone voz a este cuento sobre no caer las modas impuestas y aceptar los gustos propios'),
(9, 2, '\'Ídolos\': Bob Marley y el concierto de la paz', '6-5ecff8825df80.png', '6-5ecff8825fa4a.mpga', '2020-05-28 19:44:34', 'En este cuento, Depedro nos acerca la figura de este impulsor de una forma de vivir que siempre denunció las injusticias'),
(10, 2, 'Base after Base', 'cheers-204742_640-5ecffbc14c38d.jpeg', 'cheers-204742_640-5ecffbc14db03.mpga', '2020-05-28 19:58:25', 'good musics for gaming on YouTube'),
(11, 2, 'Blast Processing', 'girl-1990347_640-5ecffbf446594.jpeg', 'girl-1990347_640-5ecffbf447b79.mpga', '2020-05-28 19:59:16', 'This is from a video game called geometry dash, it has nothing to do with geometry\r\njust clearing this up because people seem confused and think it has something to do with actual geometry, nope just the art style of the game it comes from'),
(12, 2, 'Can\'t let go', 'music-1285165_640-5ecffc1f3ea97.jpeg', 'music-1285165_640-5ecffc1f3fec8.mpga', '2020-05-28 19:59:59', 'Is this music suppose to make me smarter @ me maths???'),
(13, 2, 'Electroman', 'musician-664432_640-5ecffc660b3e0.jpeg', 'musician-664432_640-5ecffc660c9ab.mpga', '2020-05-28 20:01:09', 'my teacher saiys i did g ood on me math homework');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `baneado` tinyint(1) NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `baneado`, `nombre`, `apellidos`) VALUES
(1, 'jimmy@user.com', '[\"ROLE_USER\"]', '$argon2id$v=19$m=65536,t=4,p=1$eS9zaEdFaE9URW9ORS5nRw$vViT6Pws9TT+iJy97OE9rDL+e7hAebxu86dxha94tgM', 0, 'Jimmy', 'Bernatán'),
(2, 'guille@user.com', '[\"ROLE_USER\"]', '$argon2id$v=19$m=65536,t=4,p=1$R2Nld2dNRHNPa2c0bWZoWA$llfBKLtik4AEqhHNZFsoJj944rNeDIXtfmo/LDz9lRs', 0, 'Guille', 'Galván');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_885DBAFAA76ED395` (`user_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `FK_885DBAFAA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
