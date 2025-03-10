<?php
require_once('../../server/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $userId = $data['id'];

    // Prepara a consulta para obter o CPF do usuário
    $sql = "SELECT cpf FROM socios WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $cpf = $row['cpf'];

        // Prepara a consulta para excluir o usuário
        $sql = "DELETE FROM socios WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Excluir as imagens e o QR code associados ao usuário
            $imageExtensions = ['.png', '.jpg', '.jpeg'];
            foreach ($imageExtensions as $extension) {
              $imagePath = "../uploads/{$cpf}{$extension}";
              if (file_exists($imagePath)) {
                  unlink($imagePath);
              }
            }

            $qrCodePath = "../qrcode/{$cpf}.png";
            if (file_exists($qrCodePath)) {
                unlink($qrCodePath);
            }

            echo json_encode(['status' => 'success', 'message' => 'Usuário excluído com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir o usuário.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Usuário não encontrado.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido.']);
}
?>