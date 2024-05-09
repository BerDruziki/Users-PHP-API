<?php

$host = 'localhost';
$port = '5432';
$dbname = 'php_api';
$username = 'postgres';
$password = 'HRp4SSbd7!';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Definição da tabela de usuários
    $sql = "CREATE TABLE IF NOT EXISTS users (
                id SERIAL PRIMARY KEY,
                nome VARCHAR(255) NOT NULL,
                telefone VARCHAR(20),
                cpf VARCHAR(14) NOT NULL,
                email VARCHAR(255) NOT NULL,
                senha VARCHAR(255) NOT NULL,
                endereco TEXT
            )";

    $pdo->exec($sql);

    //echo "Sucesso!";
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

?>
