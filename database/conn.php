<?php 

try {
    $pdo = new PDO('mysql:dbname=clients_system;host=localhost;charset=utf8', 
    'root', 
    '', 
    [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e){
    die('Erro ao conectar com o banco: '. $e->getMessage());
}