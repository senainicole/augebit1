-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/05/2025 às 18:50
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
-- Banco de dados: `augebit`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `eventos_calendario`
--

CREATE TABLE `eventos_calendario` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `data_evento` date NOT NULL,
  `hora` time NOT NULL,
  `descricao` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `eventos_calendario`
--

INSERT INTO `eventos_calendario` (`id`, `titulo`, `data_evento`, `hora`, `descricao`, `criado_em`, `atualizado_em`) VALUES
(1, 'Reunião com o Sr.Marcos', '2025-05-26', '15:30:00', '', '2025-05-22 11:50:54', '2025-05-22 11:50:54'),
(2, 'Entrega do Fornecedor Apple', '2025-05-28', '08:00:00', '', '2025-05-22 11:51:26', '2025-05-22 11:51:26');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `responsavel` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `endereco` text NOT NULL,
  `tipo_produto` varchar(100) NOT NULL,
  `categoria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `fornecedores`
--

INSERT INTO `fornecedores` (`id`, `nome`, `responsavel`, `email`, `telefone`, `endereco`, `tipo_produto`, `categoria`) VALUES
(1, 'soffia', '', 'soffia@gmail', '(11) 11234-6699', 'SP', 'celular', ''),
(2, 'Soffia', '', 'soffia@gmail.com', '(11)96462-5671', 'SP', 'eletronicos', ''),
(3, 'Apple', 'Nicole', 'nicole@gmail.com', '(11)9276-54194', 'RJ', 'Eletronicos no geral', ''),
(4, 'samsung', 'Nicole', 'nicole3@gmail.br', '1595236268', 'sp', 'Eletronicos no geral', ''),
(5, 'Dell', 'Nicole', 'nicoleDell@gmail.com', '(11) 11234-6699', 'SP', 'computadores', ''),
(6, 'Cisco', 'Marcos', 'marcosCisco@gmail.com', '(11)11234-6697', 'SP', 'Cursos onlines e eletronicos', ''),
(7, 'Apple', 'Soffia', 'soffiaApple@gmail.com', '(11)11234-6697', 'RJ', 'Eletronicos', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_estoque`
--

CREATE TABLE `itens_estoque` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `preco` decimal(10,0) NOT NULL,
  `contato` varchar(100) NOT NULL,
  `tipo_item` varchar(50) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `localizacao` varchar(100) NOT NULL,
  `observacoes` text NOT NULL,
  `fornecedor_id` int(11) NOT NULL,
  `responsavel` varchar(100) NOT NULL,
  `categoria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `itens_estoque`
--

INSERT INTO `itens_estoque` (`id`, `nome`, `preco`, `contato`, `tipo_item`, `quantidade`, `localizacao`, `observacoes`, `fornecedor_id`, `responsavel`, `categoria`) VALUES
(15, 'computador', 10000, '', 'Eletronico', 2, 'estoque fisico', 'Novos', 5, '', ''),
(16, 'Notebook', 50000, '', 'Eletronico', 0, 'estoque online', '.', 5, '', ''),
(17, 'Celular', 15000, '', 'celular', 4, 'estoque fisico', '.', 7, '', ''),
(18, 'Cursos', 20000, '', 'Curso Online', 5, 'estoque online', 'Curso Online.', 6, '', ''),
(19, 'Celular', 1000, '', 'Eletronico', 1, 'estoque fisico', 'semi-novo', 3, '', ''),
(20, 'Notebook', 25000, '', 'Eletronico', 2, 'estoque fisico', 'semi-novos', 4, '', 'eletronico');

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentacoes_estoque`
--

CREATE TABLE `movimentacoes_estoque` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `tipo_movimentacao` varchar(255) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `data_movimentacao` date NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `movimentacoes_estoque`
--

INSERT INTO `movimentacoes_estoque` (`id`, `item_id`, `tipo_movimentacao`, `quantidade`, `data_movimentacao`, `usuario_id`) VALUES
(1, 3, 'setor clientes', 2, '2025-05-05', 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `criado_em`) VALUES
(1, 'Amanda', 'mandy@gmail', '123', '2025-05-08 11:50:32'),
(2, 'Matheus', 'matheus@gmail.com', '1111', '2025-05-16 14:20:49'),
(3, 'Matheus', 'matheus@gmail.com', '1111', '2025-05-16 14:47:57'),
(4, 'Amanda', 'mandy@gmail', '2222', '2025-05-16 14:48:15');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `eventos_calendario`
--
ALTER TABLE `eventos_calendario`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `itens_estoque`
--
ALTER TABLE `itens_estoque`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `movimentacoes_estoque`
--
ALTER TABLE `movimentacoes_estoque`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `eventos_calendario`
--
ALTER TABLE `eventos_calendario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `itens_estoque`
--
ALTER TABLE `itens_estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `movimentacoes_estoque`
--
ALTER TABLE `movimentacoes_estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
