CREATE DATABASE cadastro;
USE cadastro;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    marca VARCHAR(100) NOT NULL,
    preco VARCHAR(100) NOT NULL,
    ativo bit(1) default b'1'
);
