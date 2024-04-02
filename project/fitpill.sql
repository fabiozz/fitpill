SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha_usuario` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL,
  `celular` varchar(44) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo_autenticacao` int DEFAULT NULL,
  `data_codigo_autenticacao` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usuarios` (`id`, `nome`, `usuario`, `senha_usuario`, `celular`, `codigo_autenticacao`, `data_codigo_autenticacao`) VALUES
(1, 'Fabio', 'fabio@fabio.com.br', '$2y$10$tOiFv5Kuu.PDL9uemAJFYeNK/GTnwaPpBEA9A82sLThIqdEtY6FWS', '5199999999', NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
