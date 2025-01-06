<?php

require_once('../../server/config.php');

// Receber os dados JSON da requisição
$data = json_decode(file_get_contents('php://input'), true);

// Verifica se o CPF foi enviado
if (isset($data['cpf'])) {
    $cpf = $data['cpf'];

    try {
        // Preparar a query
        $sql = "SELECT id, nome, cpf, mes, ano, valor, DATE_FORMAT(created_at, '%d/%m/%Y %H:%i:%s') as formatted_date FROM pagamentos WHERE cpf = :cpf and nome_pagamento = 'MENSALIDADE' and delet = '' ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
        
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
} else {
    echo json_encode(['status' => false, 'msg' => 'CPF não fornecido.']);
}
?>