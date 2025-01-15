-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Tempo de geração: 15/01/2025 às 00:26
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistemaportaria`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `alunos`
--

CREATE TABLE `alunos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matricula` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefone` varchar(255) NOT NULL,
  `data_nascimento` date NOT NULL,
  `responsavel_id` bigint(20) UNSIGNED NOT NULL,
  `curso_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `alunos`
--

INSERT INTO `alunos` (`id`, `matricula`, `nome`, `email`, `telefone`, `data_nascimento`, `responsavel_id`, `curso_id`, `created_at`, `updated_at`) VALUES
(1, '20211101110030', 'Moisés Henrique', 'moises.h@escolar.ifrn.edu.br', '84998051012', '2005-09-09', 1, 1, '2025-01-14 20:55:06', '2025-01-14 20:55:06'),
(2, '20211101110040', 'Pedro Henrique', 'pedro.h@escolar.ifrn.edu.br', '84994061012', '2009-05-28', 2, 2, '2025-01-14 20:55:06', '2025-01-14 20:55:06'),
(3, '20211101110050', 'João Pedro', 'joao.p@escolar.ifrn.edu.br', '84995071219', '2006-02-07', 3, 3, '2025-01-14 20:55:06', '2025-01-14 20:55:06'),
(4, '20211101110060', 'Gislaine Nascimento', 'gislaine.n@escolar.ifrn.edu.br', '84993142128', '2008-03-05', 4, 4, '2025-01-14 20:55:06', '2025-01-14 20:55:06');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursos`
--

CREATE TABLE `cursos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `curso` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `cursos`
--

INSERT INTO `cursos` (`id`, `curso`, `created_at`, `updated_at`) VALUES
(1, 'Informática para Internet', '2025-01-14 20:55:06', '2025-01-14 20:55:06'),
(2, 'Eletrotécnica', '2025-01-14 20:55:06', '2025-01-14 20:55:06'),
(3, 'Téxtil', '2025-01-14 20:55:06', '2025-01-14 20:55:06'),
(4, 'Vestuário', '2025-01-14 20:55:06', '2025-01-14 20:55:06');

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `porteiros`
--

CREATE TABLE `porteiros` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `matricula` varchar(100) NOT NULL,
  `role` varchar(255) NOT NULL,
  `turno` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_reset_required` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `porteiros`
--

INSERT INTO `porteiros` (`id`, `name`, `email`, `cpf`, `matricula`, `role`, `turno`, `password`, `password_reset_required`, `created_at`, `updated_at`) VALUES
(1, 'Manoel da Silva', 'manoeldasilva@gmail.com', '32145698722', '20250101', 'porteiro', 'matutino', '$2y$10$zrOr3jzAm5QQ9R.CfSt.G.Jq7np5ovjx8/V..cLSBfiZNdlC9RDd2', 0, '2025-01-14 20:55:06', '2025-01-14 20:56:19');

-- --------------------------------------------------------

--
-- Estrutura para tabela `registros_saidas`
--

CREATE TABLE `registros_saidas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL,
  `solicitacao` timestamp NULL DEFAULT NULL,
  `permissao` varchar(255) DEFAULT NULL,
  `saida` timestamp NULL DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `observacao_responsavel` text DEFAULT NULL,
  `aluno_id` bigint(20) UNSIGNED NOT NULL,
  `funcionario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `porteiro_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `responsaveis`
--

CREATE TABLE `responsaveis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `telefone` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `responsaveis`
--

INSERT INTO `responsaveis` (`id`, `nome`, `telefone`, `created_at`, `updated_at`) VALUES
(1, 'João Henrique', '84998051012', '2025-01-14 20:55:06', '2025-01-14 20:55:06'),
(2, 'Pedro Morais', '84994061012', '2025-01-14 20:55:06', '2025-01-14 20:55:06'),
(3, 'Jonas Alencar', '84995071219', '2025-01-14 20:55:06', '2025-01-14 20:55:06'),
(4, 'André Luiz', '84993142128', '2025-01-14 20:55:06', '2025-01-14 20:55:06');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `identificacao` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `identificacao`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Bruno Silva', 'bruno.silva@ifrn.edu.br', '3352853', NULL, '$2y$10$EQDPCqPN3GvyW7UJfCtNf..cBsyU8o2SBlSII94H35yLRK3hqSPqa', NULL, '2025-01-14 20:55:51', '2025-01-14 20:55:51');

-- --------------------------------------------------------

--
-- Estrutura para tabela `visitantes`
--

CREATE TABLE `visitantes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL,
  `saida` timestamp NULL DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `funcionario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `porteiro_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `alunos`
--
ALTER TABLE `alunos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alunos_matricula_unique` (`matricula`),
  ADD KEY `alunos_responsavel_id_foreign` (`responsavel_id`),
  ADD KEY `alunos_curso_id_foreign` (`curso_id`);

--
-- Índices de tabela `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cursos_curso_unique` (`curso`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `funcionarios_codigo_unique` (`codigo`);

--
-- Índices de tabela `porteiros`
--
ALTER TABLE `porteiros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `porteiros_email_unique` (`email`),
  ADD UNIQUE KEY `porteiros_cpf_unique` (`cpf`),
  ADD UNIQUE KEY `porteiros_matricula_unique` (`matricula`);

--
-- Índices de tabela `registros_saidas`
--
ALTER TABLE `registros_saidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registros_saidas_aluno_id_foreign` (`aluno_id`),
  ADD KEY `registros_saidas_funcionario_id_foreign` (`funcionario_id`),
  ADD KEY `registros_saidas_porteiro_id_foreign` (`porteiro_id`);

--
-- Índices de tabela `responsaveis`
--
ALTER TABLE `responsaveis`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_identificacao_unique` (`identificacao`);

--
-- Índices de tabela `visitantes`
--
ALTER TABLE `visitantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `visitantes_funcionario_id_foreign` (`funcionario_id`),
  ADD KEY `visitantes_porteiro_id_foreign` (`porteiro_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alunos`
--
ALTER TABLE `alunos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `porteiros`
--
ALTER TABLE `porteiros`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `registros_saidas`
--
ALTER TABLE `registros_saidas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `responsaveis`
--
ALTER TABLE `responsaveis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `visitantes`
--
ALTER TABLE `visitantes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `alunos`
--
ALTER TABLE `alunos`
  ADD CONSTRAINT `alunos_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `alunos_responsavel_id_foreign` FOREIGN KEY (`responsavel_id`) REFERENCES `responsaveis` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `registros_saidas`
--
ALTER TABLE `registros_saidas`
  ADD CONSTRAINT `registros_saidas_aluno_id_foreign` FOREIGN KEY (`aluno_id`) REFERENCES `alunos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registros_saidas_funcionario_id_foreign` FOREIGN KEY (`funcionario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registros_saidas_porteiro_id_foreign` FOREIGN KEY (`porteiro_id`) REFERENCES `porteiros` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `visitantes`
--
ALTER TABLE `visitantes`
  ADD CONSTRAINT `visitantes_funcionario_id_foreign` FOREIGN KEY (`funcionario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `visitantes_porteiro_id_foreign` FOREIGN KEY (`porteiro_id`) REFERENCES `porteiros` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
