<?php

require_once('../../server/config.php');

// Receber os dados JSON da requisição
$data = json_decode(file_get_contents('php://input'), true);

try {
    // Preparar a query
    $sql = "SELECT id, nome_pagamento, cpf, mes, ano, valor, tipo, DATE_FORMAT(created_at, '%d/%m/%Y %H:%i:%s') as formatted_date FROM pagamentos WHERE nome_pagamento <> 'MENSALIDADE' AND delet = '' ";
    $stmt = $pdo->prepare($sql);
    
    // Executa a consulta
    $stmt->execute();

    // Verifica se a consulta retornou resultados
    if ($stmt->rowCount() > 0) {
        // Obtém todos os dados como um array associativo
        $pagamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Converte os dados em JSON
        echo json_encode(['status' => true, 'data' => $pagamentos]);
    } else {
        // Retorna uma mensagem de erro se não houver resultados
        echo json_encode(['status' => false, 'msg' => 'Nenhum pagamento encontrado.']);
    }
} catch (PDOException $e) {
    // Retorna uma mensagem de erro em caso de exceção
    echo json_encode(['status' => false, 'msg' => 'Erro: ' . $e->getMessage()]);
}
?>