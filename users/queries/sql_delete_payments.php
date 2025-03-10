<?php
require_once('../../server/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $userId = $data['id'];

    // Prepara a consulta para obter o CPF do usuário
    $sql = "SELECT cpf FROM pagamentos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $cpf = $row['cpf'];

        // Prepara a consulta para excluir o usuário
        $sql = "DELETE FROM pagamentos WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Pagamento excluído com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir o Pagamento.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Pagamento não encontrado.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido.']);
}
?>