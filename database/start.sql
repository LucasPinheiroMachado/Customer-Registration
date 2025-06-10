CREATE DATABASE IF NOT EXISTS clients_system;
USE clients_system;

CREATE TABLE client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    cpf_cnpj VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20)
);

INSERT INTO client (name, cpf_cnpj, email, phone) VALUES
('João Silva', '123.456.789-00', 'joao.silva@email.com', '11999990001'),
('Maria Oliveira', '987.654.321-00', 'maria.oliveira@email.com', '21988887777'),
('Pedro Santos', '456.123.789-00', 'pedro.santos@email.com', '31977776666'),
('Ana Souza', '321.654.987-00', 'ana.souza@email.com', '41966665555'),
('Carlos Lima', '159.753.486-00', 'carlos.lima@email.com', '51955554444');
