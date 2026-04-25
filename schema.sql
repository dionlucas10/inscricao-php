-- Criar o banco de dados
CREATE DATABASE IF NOT EXISTS tela_login;
USE tela_login;

-- Criar a tabela de inscrições
CREATE TABLE IF NOT EXISTS inscricoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    tipo_participante VARCHAR(50) NOT NULL,
    interesses JSON,
    mensagem TEXT,
    data_inscricao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criar índice no email para otimizar buscas
CREATE INDEX idx_email ON inscricoes(email);
