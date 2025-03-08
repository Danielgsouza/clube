<?php

require_once('../../server/config.php');

// Receber os dados do formulário (no caso, o CPF)
$ArrayDados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$cpf = $ArrayDados['socio-id'];

try {
    // Preparar a query para pegar o último pagamento
    $sql = "SELECT * FROM pagamentos WHERE cpf = :cpf and delet = ''  ORDER BY ANO DESC, MES DESC LIMIT 1 ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    
    // Executa a consulta
    $stmt->execute();

    // Verifica se a consulta retornou resultados
    if ($stmt->rowCount() > 0) {
        // Obtém os dados como um array associativo
        $pagamento = $stmt->fetch(PDO::FETCH_ASSOC);
      
        // Verifica a data do último pagamento
        //     $mes_pagamento = $pagamento['mes'];
        //     $ano_pagamento = date('Y'); // Supondo que o ano atual seja o ano do pagamento
        //     $data_pagamento = DateTime::createFromFormat('Y-m', "$ano_pagamento-$mes_pagamento");
        //     $data_atual = new DateTime();
    
        
        //     // Comparar o mês e o ano do pagamento com o mês e o ano atuais
        //     if (($data_pagamento->format('Y-m') - $data_atual->format('Y-m')) > 45) {
        //         // Pagamento do mês atual
        //         echo json_encode([
        //             'status' => true,
        //             'data' => [$pagamento]
        //         ]);
        //     } else {
        //         // Pagamento desatualizado
        //         echo json_encode([
        //             'status' => false,
        //             'data' => [$pagamento]
        //         ]);
        //     }
        // } else {
        //     // Retorna mensagem de erro caso não haja resultados
        //     echo json_encode(['status' => false, 'data' => []]);
        // }
        // Verifica a data do último pagamento
        $mes_pagamento = $pagamento['mes'];
        $ano_pagamento = date('Y'); // Supondo que o ano atual seja o ano do pagamento
        $data_pagamento = DateTime::createFromFormat('Y-m', "$ano_pagamento-$mes_pagamento");
        $data_atual = new DateTime();

        // Adiciona 45 dias à data do pagamento para comparar com a data atual
        $data_limite = clone $data_pagamento; // Clona a data de pagamento para não modificar a original
        $data_limite->add(new DateInterval('P45D')); // Adiciona 45 dias à data do pagamento

        // Comparar se a data atual é superior a 45 dias após o pagamento
        if ($data_atual > $data_limite) {
            // Prazo superior a 45 dias
            echo json_encode([
                'status' => false,
                'data' => [$pagamento]
            ]);
        } else {
            // Prazo dentro de 45 dias
            echo json_encode([
                'status' => true,
                'data' => [$pagamento]
            ]);
        }
    }
} catch (PDOException $e) {
    // Retorna mensagem de erro em caso de exceção
    echo json_encode(['status' => false, 'msg' => 'Erro: ' . $e->getMessage()]);
}
?>