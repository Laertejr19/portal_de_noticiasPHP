CREATE DATABASE IF NOT EXISTS portal_ambiental CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portal_ambiental;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin', 'user') DEFAULT 'user',
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    noticia TEXT NOT NULL,
    data DATETIME DEFAULT CURRENT_TIMESTAMP,
    autor INT NOT NULL,
    imagem VARCHAR(255),
    FOREIGN KEY (autor) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Inserir usuários de exemplo COM SENHAS EM TEXTO PURO
INSERT INTO usuarios (nome, email, senha, tipo) VALUES
('Administrador', 'admin@portal.com', 'admin123', 'admin'),
('Ana Verde', 'ana@verde.com', 'password', 'user'),
('Pedro Eco', 'pedro@eco.com', 'password', 'user');

INSERT INTO noticias (titulo, noticia, autor, imagem) VALUES
(
    'Amazônia atinge menor desmatamento em 9 anos',
    'O desmatamento na Amazônia Legal caiu 30,6% em 2025, segundo dados do INPE. Ações de fiscalização e políticas ambientais fortalecidas contribuíram para o resultado histórico...',
    1,
    'imagens/amazonia.jpg'
),
(
    'Brasil lidera reciclagem de latinhas na América Latina',
    'Com taxa de 98,7% de reciclagem de latas de alumínio, o Brasil se consolida como referência em economia circular no continente. O setor gera mais de 100 mil empregos diretos.',
    2,
    'imagens/reciclagem.jpg'
);