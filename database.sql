-- --------------------------------------------------------

--
-- Table structure for table `pendencias_financeiras`
--

CREATE TABLE `pendencias_financeiras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `status` enum('pendente','pago','cancelado') NOT NULL DEFAULT 'pendente',
  `data_vencimento` date NOT NULL,
  `data_pagamento` date DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `pendencias_financeiras_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------- 