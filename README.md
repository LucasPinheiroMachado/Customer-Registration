# Sistema de Gerenciamento de Clientes

Este projeto consiste em um CRUD (Create, Read, Update, Delete) completo para gerenciamento de clientes, com uma API RESTful em PHP e um frontend em jQuery/Bootstrap.

## Funcionalidades

- Listagem de todos os clientes
- Visualização de detalhes de um cliente específico
- Cadastro de novos clientes
- Edição de clientes existentes
- Exclusão de clientes
- Formatação de CPF/CNPJ e telefone
- Notificações de ações bem-sucedidas ou erros

## Tecnologias Utilizadas

- **Frontend**:
  - jQuery para requisições AJAX e manipulação do DOM
  - Bootstrap para estilização e componentes UI
  - Máscaras para formatação de CPF/CNPJ e telefone

- **Backend**:
  - PHP para a API RESTful
  - MySQL para armazenamento de dados
  - PDO para conexão com o banco de dados
    
## Estrutura do Projeto
- **MVC**:
  - Model
  - View
  - Controller

## Pré-requisitos

- PHP 7.0 ou superior
- MySQL 5.7 ou superior

## Instalação e Configuração

### 1. Configurar o banco de dados

1. Crie o banco de dados ultilizando o arquivo `start.sql` localizado na pasta database.
2. Configure o arquivo `conn.php` localizado na pasta database conforme necessário.

### 2. Iniciar o servidor

1. Caso tenha php instalado direto na máquina execute o comando no terminal na raíz do projeto "php -S localhost:8000".
2. Caso tenha um servidor apache inicie o PHP e o Mysql.

### 3. Configurar rotas

1. Todas as rotas para realizar metodos GET, POST, PUT e DELETE estão iniciadas com "http://localhost:8000", mude conforme a localização da sua pasta e também a url do seu servidor.
