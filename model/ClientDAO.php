<?php

require_once __DIR__ . '/../database/conn.php';
require_once __DIR__ . '/Client.php';

class ClientDAO {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAllClients() {
        try {
            $sql = 'SELECT * FROM client';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $clients = [];
            foreach ($results as $row) {
                $clients[] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'cpf_cnpj' => $row['cpf_cnpj'],
                    'email' => $row['email'],
                    'phone' => $row['phone']
                ];
            }
            return $clients;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar clientes: " . $e->getMessage());
        }
    }


    public function getClientById($id) {
        try {
            $sql = 'SELECT * FROM client WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'cpfCnpj' => $row['cpf_cnpj'],
                    'email' => $row['email'],
                    'phone' => $row['phone'],
                ];
            }
            return null;
        } catch (PDOException $e) {
            die("Erro ao buscar cliente: " . $e->getMessage());
        }
    }

    public function insertClient(Client $client) {
        try {
            $sql = 'INSERT INTO client (name, cpf_cnpj, email, phone) VALUES (:name, :cpf_cnpj, :email, :phone)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':name', $client->getName(), PDO::PARAM_STR);
            $stmt->bindValue(':cpf_cnpj', $client->getCpfCnpj(), PDO::PARAM_STR);
            $stmt->bindValue(':email', $client->getEmail(), PDO::PARAM_STR);
            $stmt->bindValue(':phone', $client->getPhone(), PDO::PARAM_STR);
            $stmt->execute();

            $client->setId($this->pdo->lastInsertId());

            return $client;
        } catch (PDOException $e) {
            die("Erro ao inserir cliente: " . $e->getMessage());
        }
    }

    public function updateClient(Client $client) {
        try {
            $sql = 'UPDATE client SET name = :name, cpf_cnpj = :cpf_cnpj, email = :email, phone = :phone WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $client->getId(), PDO::PARAM_INT);
            $stmt->bindValue(':name', $client->getName(), PDO::PARAM_STR);
            $stmt->bindValue(':cpf_cnpj', $client->getCpfCnpj(), PDO::PARAM_STR);
            $stmt->bindValue(':email', $client->getEmail(), PDO::PARAM_STR);
            $stmt->bindValue(':phone', $client->getPhone(), PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Erro ao atualizar cliente: " . $e->getMessage());
        }
    }

    public function deleteClient($id) {
        try {
            $sql = 'DELETE FROM client WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Erro ao deletar cliente: " . $e->getMessage());
        }
    }
}
