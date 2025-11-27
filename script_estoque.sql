-- ========================================================
-- 1. ESTRUTURA DO BANCO DE DADOS (DDL)
-- ========================================================

DROP DATABASE IF EXISTS sistema_estoque;
CREATE DATABASE sistema_estoque;
USE sistema_estoque;

-- Tabela de Usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL, 
    tipo ENUM('admin', 'cliente') DEFAULT 'cliente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Categorias
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela de Fornecedores
CREATE TABLE fornecedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_empresa VARCHAR(100) NOT NULL,
    cnpj VARCHAR(20),
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    endereco VARCHAR(150),
    cidade VARCHAR(100),
    estado VARCHAR(2),
    observacoes TEXT,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela de Produtos
-- AJUSTE IMPORTANTE: id_fornecedor aceita NULL e tem ON DELETE SET NULL
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(50) NOT NULL,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    quantidade INT DEFAULT 0,
    estoque_minimo INT DEFAULT 5,
    
    id_categoria INT,
    id_fornecedor INT NULL, -- Pode ficar vazio se o fornecedor for excluído
    id_usuario INT NOT NULL,
    
    FOREIGN KEY (id_categoria) REFERENCES categorias(id) ON DELETE SET NULL,
    FOREIGN KEY (id_fornecedor) REFERENCES fornecedores(id) ON DELETE SET NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela de Movimentações
CREATE TABLE movimentacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('entrada', 'saida') NOT NULL,
    quantidade INT NOT NULL,
    data_movimentacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    motivo VARCHAR(255),
    
    id_produto INT NOT NULL,
    id_usuario INT NOT NULL,
    
    FOREIGN KEY (id_produto) REFERENCES produtos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- ========================================================
-- 2. POVOAMENTO DE DADOS (DML) - CENÁRIO REAL
-- ========================================================

-- --------------------------------------------------------
-- USUÁRIOS (1 Admin + 3 Clientes)
-- Senha padrão para todos: '123'
-- --------------------------------------------------------
INSERT INTO usuarios (id, nome, email, senha, tipo) VALUES 
(1, 'Administrador Geral', 'admin@sistema.com', '123', 'admin'),
(2, 'TechZone Eletrônicos', 'tech@cliente.com', '123', 'cliente'),
(3, 'Moda Fashion Store', 'moda@cliente.com', '123', 'cliente'),
(4, 'Mercado Fresco', 'mercado@cliente.com', '123', 'cliente');


-- ========================================================
-- DADOS DO CLIENTE 1: TechZone (ID 2)
-- ========================================================

-- Categorias
INSERT INTO categorias (id, nome, id_usuario) VALUES 
(1, 'Computadores', 2),
(2, 'Periféricos', 2),
(3, 'Smartphones', 2),
(4, 'Games', 2);

-- Fornecedores
INSERT INTO fornecedores (id, nome_empresa, email, cnpj, telefone, id_usuario) VALUES 
(1, 'Distribuidora Global Tech', 'contato@globaltech.com', '11.111.111/0001-11', '11999990001', 2),
(2, 'Mega Hardware Import', 'vendas@megahardware.com', '22.222.222/0001-22', '11999990002', 2);

-- Produtos (8 itens)
INSERT INTO produtos (sku, nome, descricao, preco, quantidade, estoque_minimo, id_categoria, id_fornecedor, id_usuario) VALUES 
('PC-GAMER-01', 'PC Gamer i7', 'Core i7, 16GB RAM, RTX 3060', 5500.00, 5, 2, 1, 2, 2),
('NOTE-DELL', 'Notebook Dell Inspiron', 'i5, 8GB RAM, SSD 256GB', 3200.00, 8, 3, 1, 1, 2),
('MOUSE-LOGI', 'Mouse Logitech G Pro', 'Mouse sem fio gamer', 450.00, 20, 5, 2, 2, 2),
('TECLADO-MEC', 'Teclado Mecânico RGB', 'Switch Blue, ABNT2', 250.00, 15, 5, 2, 1, 2),
('IPHONE-13', 'iPhone 13 128GB', 'Cor Azul, Novo', 4200.00, 4, 2, 3, 1, 2),
('SAMSUNG-S22', 'Galaxy S22 Ultra', 'Preto, 256GB', 3800.00, 6, 2, 3, 1, 2),
('PS5-CONSOLE', 'PlayStation 5', 'Versão com disco', 3900.00, 3, 1, 4, 2, 2),
('XBOX-SERIES', 'Xbox Series X', '1TB SSD', 3800.00, 4, 1, 4, 2, 2);


-- ========================================================
-- DADOS DO CLIENTE 2: Moda Fashion (ID 3)
-- ========================================================

-- Categorias
INSERT INTO categorias (id, nome, id_usuario) VALUES 
(5, 'Camisetas', 3),
(6, 'Calças', 3),
(7, 'Calçados', 3),
(8, 'Acessórios', 3);

-- Fornecedores
INSERT INTO fornecedores (id, nome_empresa, email, cnpj, telefone, id_usuario) VALUES 
(3, 'Têxtil Santa Catarina', 'pedidos@textilsc.com.br', '33.333.333/0001-33', '47999990003', 3),
(4, 'Couros do Sul', 'contato@courosul.com.br', '44.444.444/0001-44', '51999990004', 3);

-- Produtos (8 itens)
INSERT INTO produtos (sku, nome, descricao, preco, quantidade, estoque_minimo, id_categoria, id_fornecedor, id_usuario) VALUES 
('CAM-BASIC-B', 'Camiseta Básica Branca', 'Algodão Pima, Tam M', 89.90, 50, 10, 5, 3, 3),
('CAM-EST-ROCK', 'Camiseta Estampa Rock', 'Preta, Tam G', 120.00, 20, 5, 5, 3, 3),
('JEANS-SKINNY', 'Calça Jeans Skinny', 'Azul escuro, Tam 40', 180.00, 15, 5, 6, 3, 3),
('JOGGER-BEGE', 'Calça Jogger Bege', 'Sarja, Tam 42', 150.00, 12, 4, 6, 3, 3),
('TENIS-URB', 'Tênis Urbano Branco', 'Couro sintético, Tam 41', 250.00, 8, 3, 7, 4, 3),
('BOTA-COURO', 'Bota Chelsea', 'Couro legítimo Marrom', 350.00, 5, 2, 7, 4, 3),
('CINTO-PRETO', 'Cinto de Couro', 'Fivela prata', 60.00, 25, 5, 8, 4, 3),
('BONE-ABA', 'Boné Aba Curva', 'Preto liso', 45.00, 30, 8, 8, 3, 3);


-- ========================================================
-- DADOS DO CLIENTE 3: Mercado Fresco (ID 4)
-- ========================================================

-- Categorias
INSERT INTO categorias (id, nome, id_usuario) VALUES 
(9, 'Hortifruti', 4),
(10, 'Padaria', 4),
(11, 'Bebidas', 4),
(12, 'Limpeza', 4);

-- Fornecedores
INSERT INTO fornecedores (id, nome_empresa, email, cnpj, telefone, id_usuario) VALUES 
(5, 'Fazenda Doce Campo', 'vendas@docecampo.com', '55.555.555/0001-55', '31999990005', 4),
(6, 'Atacadão das Bebidas', 'comercial@atacadao.com', '66.666.666/0001-66', '11999990006', 4);

-- Produtos (8 itens)
INSERT INTO produtos (sku, nome, descricao, preco, quantidade, estoque_minimo, id_categoria, id_fornecedor, id_usuario) VALUES 
('MACA-GALA', 'Maçã Gala', 'Preço por KG', 8.50, 100, 20, 9, 5, 4),
('BANANA-PRATA', 'Banana Prata', 'Preço por KG', 6.90, 80, 15, 9, 5, 4),
('PAO-FRANCES', 'Pão Francês', 'Unidade fresca', 0.80, 200, 50, 10, 5, 4),
('BOLO-CHOC', 'Bolo de Chocolate', 'Fatia', 12.00, 15, 5, 10, 5, 4),
('COCA-2L', 'Coca-Cola 2L', 'Original', 9.50, 60, 12, 11, 6, 4),
('SUCO-LAR', 'Suco de Laranja', 'Integral 1L', 14.00, 30, 6, 11, 5, 4),
('SABAO-PO', 'Sabão em Pó Omo', 'Caixa 1kg', 18.90, 40, 10, 12, 6, 4),
('DETERGENTE', 'Detergente Ypê', 'Neutro 500ml', 2.50, 100, 20, 12, 6, 4);

-- ========================================================
-- ALGUNS LOGS DE MOVIMENTAÇÃO (Para o Dashboard não ficar vazio)
-- ========================================================

-- Cliente 2 (Tech) fazendo estoque inicial
INSERT INTO movimentacoes (tipo, quantidade, motivo, id_produto, id_usuario) VALUES
('entrada', 5, 'Estoque Inicial', 1, 2),
('entrada', 8, 'Estoque Inicial', 2, 2);

-- Cliente 3 (Moda) vendendo algo
INSERT INTO movimentacoes (tipo, quantidade, motivo, id_produto, id_usuario) VALUES
('entrada', 55, 'Compra de Estoque', 9, 3), -- Entrou 55 camisetas
('saida', 5, 'Venda no Balcão', 9, 3);    -- Vendeu 5, sobrou 50