<?php

require_once('../../server/config.php');

// Receber os dados JSON da requisição
$data = json_decode(file_get_contents('php://input'), true);

// Verifica se o titularId foi enviado
if (isset($data['titularId'])) {
    $titularId = $data['titularId'];

    try {
        // Preparar a query
        $sql = "SELECT id, nome, cpf, titulo, DATE_FORMAT(data_nascimento, '%d/%m/%Y') as data_nascimento, telefone, status, titular_id, DATE_FORMAT(created_at, '%d/%m/%Y %H:%i:%s') as formatted_date FROM dependentes WHERE titular_id = :titular_id and delet = '' ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':titular_id', $titularId, PDO::PARAM_INT);
        
        // Executa a consulta
        $stmt->execute();
        // Verifica se a consulta retornou resultados
        if ($stmt->rowCount() > 0) {
            // Obtém todos os dados como um array associativo
            $dependents = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Converte os dados em JSON
            echo json_encode(['status' => true, 'data' => $dependents]);
        } else {
            // Retorna uma mensagem de erro se não houver resultados
            echo json_encode(['status' => false, 'msg' => 'Nenhum dependente encontrado.']);
        }
    } catch (PDOException $e) {
        // Retorna uma mensagem de erro em caso de exceção
        echo json_encode(['status' => false, 'msg' => 'Erro: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => false, 'msg' => 'Titular ID não fornecido.']);
}
?>