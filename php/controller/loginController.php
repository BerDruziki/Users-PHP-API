<?php

require_once __DIR__ . '/../data/pgsql.php'; 
require_once __DIR__ . '/../models/loginDTO.php';

class LoginController {
    private $conn;
    private $secretKey = "seu_segredo_aqui"; // Chave secreta para assinar o token JWT

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
                // Gere um token JWT
                $payload = [
                    'user_id' => $user['id'],
                    'email' => $user['email'],
                    // Adicione outras informações do usuário conforme necessário
                ];
                $jwt = $this->generateJWT($payload); // Gere o token JWT

                // Retorne o token JWT e uma mensagem indicando que o login foi bem-sucedido
                return [
                    'statusCode' => 200,
                    'body' => json_encode([
                        'token' => $jwt,
                        'message' => 'Login bem-sucedido.'
                    ])
                ];
            } else {
                // Senha incorreta
                return [
                    'statusCode' => 401,
                    'body' => json_encode([
                        'error' => 'Email ou senha incorretos.'
                    ])
                ];
            }
        } else {
            // Usuário não encontrado
            return [
                'statusCode' => 401,
                'body' => json_encode([
                    'error' => 'Email ou senha incorretos.'
                ])
            ];
        }
    }

    private function generateJWT($payload) {
        // Codifica o payload em JSON
        $encodedPayload = json_encode($payload);
        // Codifica o payload em Base64
        $encodedPayloadBase64 = base64_encode($encodedPayload);

        // Calcula o HMAC SHA-256 do payload usando a chave secreta
        $signature = hash_hmac('sha256', $encodedPayloadBase64, $this->secretKey, true);
        // Codifica a assinatura em Base64
        $encodedSignatureBase64 = base64_encode($signature);

        // Forma o token JWT combinando o cabeçalho, o payload e a assinatura
        $jwt = $encodedPayloadBase64 . '.' . $encodedSignatureBase64;
        return $jwt;
    }
}

?>
