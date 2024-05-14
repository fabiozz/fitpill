SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `email` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `senha_usuario` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_token` varchar(64) DEFAULT NULL UNIQUE,
  `troca_senha_token` varchar(64) DEFAULT NULL UNIQUE,
  `segredo_2fa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
