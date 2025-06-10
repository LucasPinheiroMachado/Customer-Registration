<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

require_once __DIR__ . '/../model/Client.php';
require_once __DIR__ . '/../model/ClientDAO.php';

$url = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$erClients = '/^.*\/clients\/?$/i';
$erOneClient = '/^.*\/client(?:\/([0-9]{1,50}))?\/?$/i';

$matches = [];

header('Content-Type: application/json');

if (preg_match($erClients, $url) && $method == 'GET') {
    try {
        $clientDAO = new ClientDAO();
        $clients = $clientDAO->getAllClients();
        echo json_encode($clients);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['Erro ao consultar clientes']);
    }

} elseif (preg_match($erOneClient, $url, $matches) && $method == 'GET') {
    $id = $matches[1];

    try {
        $clientDAO = new ClientDAO();
        $client = $clientDAO->getClientById($id);
        echo json_encode($client);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['Erro ao consultar cliente']);
    }

} elseif (preg_match($erOneClient, $url) && $method == 'POST') {
    $content = file_get_contents('php://input');
    $data = json_decode($content, true);

    try {
        $client = new Client(null, $data['name'] ?? '', $data['cpfCnpj'] ?? '', $data['email'] ?? '', $data['phone'] ?? '');
        $clientDAO = new ClientDAO();
        $clientDAO->insertClient($client);
        http_response_code(201);
        echo json_encode(['message' => 'Cadastro realizado com sucesso']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['Erro ao cadastrar cliente']);
    }

} elseif (preg_match($erOneClient, $url, $matches) && $method == 'PUT') {
    $id = $matches[1];
    $content = file_get_contents('php://input');
    $data = json_decode($content, true);

    try {
        $client = new Client($id, $data['name'] ?? '', $data['cpfCnpj'] ?? '', $data['email'] ?? '', $data['phone'] ?? '');
        $clientDAO = new ClientDAO();
        $clientDAO->updateClient($client);
        http_response_code(200);
        echo json_encode(['message' => 'Cliente atualizado com sucesso']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['Erro ao atualizar cliente']);
    }

} elseif (preg_match($erOneClient, $url, $matches) && $method == 'DELETE') {
    $id = $matches[1];

    try {
        $clientDAO = new ClientDAO();
        $clientDAO->deleteClient($id);
        http_response_code(200);
        echo json_encode(['message' => 'Cliente deletado com sucesso']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['Erro ao deletar cliente']);
    }

} else {
    http_response_code(404);
    echo json_encode(['Rota não encontrada']);
}
