<?php

require_once('../../server/config.php');

// Receber os dados do formulário (no caso, o CPF)
$ArrayDados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$cpf = $ArrayDados['socio-id'];

try {
    // Preparar a query para pegar o último pagamento
    $sql = "SELECT * FROM pagamentos WHERE cpf = :cpf and delet = '' ORDER BY created_at DESC ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    
    // Executa a consulta
    $stmt->execute();

    // Verifica se a consulta retornou resultados
    if ($stmt->rowCount() > 0) {
        // Obtém os dados como um array associativo
        $pagamento = $stmt->fetch(PDO::FETCH_ASSOC);
      
        // Verifica a data do último pagamento
        $mes_pagamento = $pagamento['mes'];
        $ano_pagamento = date('Y'); // Supondo que o ano atual seja o ano do pagamento
        $data_pagamento = DateTime::createFromFormat('Y-m', "$ano_pagamento-$mes_pagamento");
        $data_atual = new DateTime();
 
        // Comparar o mês e o ano do pagamento com o mês e o ano atuais
        if ($data_pagamento->format('Y-m') == $data_atual->format('Y-m')) {
            // Pagamento do mês atual
            echo json_encode([
                'status' => true,
                'data' => [$pagamento]
            ]);
        } else {
            // Pagamento desatualizado
            echo json_encode([
                'status' => false,
                'data' => [$pagamento]
            ]);
        }
    } else {
        // Retorna mensagem de erro caso não haja resultados
        echo json_encode(['status' => false, 'data' => []]);
    }
} catch (PDOException $e) {
    // Retorna mensagem de erro em caso de exceção
    echo json_encode(['status' => false, 'msg' => 'Erro: ' . $e->getMessage()]);
}
?>