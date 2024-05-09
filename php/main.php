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

else {
    // Endpoint não encontrado
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Endpoint não encontrado']);
}

?>