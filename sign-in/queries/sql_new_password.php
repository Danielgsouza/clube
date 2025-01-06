<?php
require_once('../../server/config.php');

// Definir o cabeçalho da resposta como JSON
header('Content-Type: application/json');

// Receber os dados JSON da requisição
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['token']) && isset($data['password'])) {
    $token = $data['token'];
    $newPassword = $data['password'];
    $currentTime = time(); // Armazena o resultado de time() em uma variável

    // Verificar se o token é válido e não expirou
    $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = :token AND expires > :time");
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':time', $currentTime, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $email = $user['email'];
        
        // Hash da nova senha
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Atualiza a senha do usuário
        $stmt = $pdo->prepare("UPDATE usuarios SET senha = :senha WHERE email = :email");
        $stmt->bindParam(':senha', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // Exclui o token
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = :token");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(["message" => "Senha alterada com sucesso!"]);
    } else {
        echo json_encode(["message" => "Token inválido ou expirado."]);
    }
} else {
    echo json_encode(["message" => "Dados inválidos."]);
}
?>