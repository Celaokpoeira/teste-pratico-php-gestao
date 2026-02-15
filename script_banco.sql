CREATE DATABASE IF NOT EXISTS gestao_compras;
USE gestao_compras;

CREATE TABLE fornecedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    cnpj VARCHAR(18) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    codigo_interno VARCHAR(50) NOT NULL UNIQUE,
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE produto_fornecedor (
    produto_id INT NOT NULL,
    fornecedor_id INT NOT NULL,
    PRIMARY KEY (produto_id, fornecedor_id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id) ON DELETE CASCADE
);

-- Inserindo alguns dados de teste
INSERT INTO fornecedores (nome, cnpj, email, telefone) VALUES ('Tech Distribuidora', '11.111.111/0001-11', 'contato@tech.com', '11999999999');
INSERT INTO produtos (nome, descricao, codigo_interno) VALUES ('Notebook Dell', 'Core i7, 16GB RAM', 'NTB-001');