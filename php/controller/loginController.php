<?php
require_once __DIR__ . '/../data/pgsql.php'; 
require_once __DIR__ . '/../models/loginDTO.php';

class LoginController {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }
    
    public function login(LoginDTO $loginDTO) {
        // Recupere o email e a senha do DTO
        $email = $loginDTO->getEmail();
        $senha = $loginDTO->getSenha();

        // Consulta SQL para verificar se o usuário existe
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Verifica se o usuário existe
        if ($stmt->rowCount() > 0) {
            // Recupere os dados do usuário
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verifica se a senha está correta
            if (password_verify($senha, $user['senha'])) {
                // Senha correta, usuário autenticado com sucesso
                echo "Login bem-sucedido!";
                // Você pode realizar outras ações aqui, como redirecionar o usuário para uma página interna.
            } else {
                // Senha incorreta
                echo "Senha incorreta.";
            }
        } else {
            // Usuário não encontrado
            echo "Usuário não encontrado.";
        }
    }
}
?>
