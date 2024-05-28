<?php

error_reporting(1);
ini_set('error_reporting', E_ALL);

require_once __DIR__ . '/data/pgsql.php';

$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];
$endpoint_path = parse_url($request_uri, PHP_URL_PATH);

// criar um novo usuário 
// http://localhost/php/main.php/criar-usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($endpoint_path, '/criar-usuario') !== false) {
    require_once 'controller/userController.php';
} 

// deletar um usuário
// http://localhost/php/main.php/deletar-usuario?id=1
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && strpos($endpoint_path, '/deletar-usuario') !== false) {
    require_once 'controller/userController.php';
}

// obter um usuário
//http://localhost/php/main.php/obter-usuario?id=1
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && strpos($endpoint_path, '/obter-usuario') !== false) {
    require_once 'controller/userController.php';
}

// editar um usuário 
// http://localhost/php/main.php/editar-usuario
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && strpos($endpoint_path, '/editar-usuario') !== false) {
    require_once 'controller/userController.php';
}

// logar o usuário
// http://localhost/php/main.php/login
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['REQUEST_URI'], '/login') !== false) {
    // Verifica se o corpo da solicitação contém dados
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (isset($data['email'], $data['senha'])) {
        require_once 'models/loginDTO.php'; // Inclui o arquivo que contém a classe loginDTO
        require_once 'controller/loginController.php'; // Inclui o arquivo que contém a classe LoginController
        $loginDTO = new loginDTO($data['email'], $data['senha']);
        $loginController = new LoginController($pdo);
        $response = $loginController->login($loginDTO);

        // Verifica se a resposta está definida
        if (isset($response)) {
            // Define o código de resposta HTTP com base na resposta do controlador
            http_response_code($response['statusCode']);
            
            // Retorna o corpo da resposta como JSON
            echo json_encode($response['body']);
        } else {
            // Se a resposta não estiver definida, retorne um erro interno do servidor
            http_response_code(500);
            echo json_encode(['error' => 'Erro interno do servidor.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Os campos de email e senha são obrigatórios.']);
    }
}


else {
    // Endpoint não encontrado
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Endpoint não encontrado']);
}

?>