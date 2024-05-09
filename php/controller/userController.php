<?php

require_once __DIR__ . '/../data/pgsql.php'; 
require_once __DIR__ . '/../models/user.php';

// Verificar o método da requisição
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['nome'], $data['telefone'], $data['cpf'], $data['email'], $data['senha'], $data['endereco'])) {
        $senhaCriptografada = password_hash($data['senha'], PASSWORD_DEFAULT);
        $user = new user(null, $data['nome'], $data['telefone'], $data['cpf'], $data['email'], $senhaCriptografada, $data['endereco']);

        $sql = "INSERT INTO users (nome, telefone, cpf, email, senha, endereco) VALUES (?, ?, ?, ?, ?, ?)";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user->getNome(), $user->getTelefone(), $user->getCpf(), $user->getEmail(), $user->getSenha(), $user->getEndereco()]);
            $user_id = $pdo->lastInsertId();
            http_response_code(201);
            echo json_encode(array("message" => "Usuário criado com sucesso.", "user_id" => $user_id));
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array("message" => "Erro ao criar usuário: " . $e->getMessage()));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Todos os campos são obrigatórios."));
    }

} elseif ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        try {
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            if ($stmt->rowCount() > 0) {
                http_response_code(200);
                echo json_encode(array("message" => "Usuário deletado com sucesso."));
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Usuário não encontrado."));
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array("message" => "Erro ao deletar usuário: " . $e->getMessage()));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "ID do usuário não fornecido."));
    }

} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        
        try {
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                http_response_code(200);
                echo json_encode($user);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Usuário não encontrado."));
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array("message" => "Erro ao obter usuário: " . $e->getMessage()));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "ID do usuário não fornecido."));
    }

} elseif ($_SERVER["REQUEST_METHOD"] == "PUT") {

    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['id'], $data['dados'])) {
        $id = $data['id'];
        $novosDados = $data['dados'];

        if (isset($novosDados['nome'], $novosDados['telefone'], $novosDados['cpf'], $novosDados['email'], $novosDados['senha'], $novosDados['endereco'])) {
            try {
                $sql = "UPDATE users SET nome = ?, telefone = ?, cpf = ?, email = ?, senha = ?, endereco = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$novosDados['nome'], $novosDados['telefone'], $novosDados['cpf'], $novosDados['email'], $novosDados['senha'], $novosDados['endereco'], $id]);
                if ($stmt->rowCount() > 0) {
                    http_response_code(200);
                    echo json_encode(array("message" => "Informações do usuário atualizadas com sucesso."));
                } else {
                    http_response_code(404);
                    echo json_encode(array("message" => "Usuário não encontrado."));
                }
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(array("message" => "Erro ao atualizar informações do usuário: " . $e->getMessage()));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Todos os campos dos novos dados são obrigatórios."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "ID do usuário e novos dados são obrigatórios."));
    }
}

?>