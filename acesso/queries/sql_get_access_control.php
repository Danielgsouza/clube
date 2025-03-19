<?php

require_once('../../server/config.php');

// Receber os dados do formulário (no caso, o CPF)
$ArrayDados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$cpf = $ArrayDados['socio-id'];

try {
    // Preparar a query para pegar o último pagamento e calcular a diferença em dias
    $sql = "SELECT *, DATEDIFF(CURDATE(), DATE_FORMAT(CONCAT(ano, '-', mes, '-01'), '%Y-%m-%d')) AS dias_desde_pagamento
            FROM pagamentos
            WHERE cpf = :cpf AND delet = '' 
            ORDER BY ANO DESC, MES DESC LIMIT 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    
    // Executa a consulta
    $stmt->execute();

    // Verifica se a consulta retornou resultados
    if ($stmt->rowCount() > 0) {
        // Obtém os dados como um array associativo
        $pagamento = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // A diferença de dias desde o pagamento
        $dias_desde_pagamento = $pagamento['dias_desde_pagamento'];

        // Verifica se passaram mais de 60 dias
        if ($dias_desde_pagamento > 60) {
            // Prazo superior a 60 dias
            echo json_encode([
                'status' => false,
                'data' => [$pagamento]
            ]);
        } else {
            // Prazo dentro de 60 dias
            echo json_encode([
                'status' => true,
                'data' => [$pagamento]
            ]);
        }
    } else {
        echo json_encode(['status' => false, 'msg' => 'Nenhum pagamento encontrado.']);
    }
} catch (PDOException $e) {
    // Retorna mensagem de erro em caso de exceção
    echo json_encode(['status' => false, 'msg' => 'Erro: ' . $e->getMessage()]);
}
?>
