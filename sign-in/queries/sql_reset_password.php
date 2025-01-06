<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $mysqli = new mysqli("localhost", "username", "password", "database");

    // Verificar se o token é válido e não expirou
    $stmt = $mysqli->prepare("SELECT email FROM password_resets WHERE token = ? AND expires > ?");
    $stmt->bind_param("si", $token, time());
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $email = $user['email'];
        
        // Formulário para redefinir a senha
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['password'];
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Atualiza a senha do usuário
            $stmt = $mysqli->prepare("UPDATE usuarios SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashedPassword, $email);
            $stmt->execute();

            // Exclui o token
            $stmt = $mysqli->prepare("DELETE FROM password_resets WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();

            echo "Senha alterada com sucesso!";
        }
    } else {
        echo "Token inválido ou expirado.";
    }
}
?>
