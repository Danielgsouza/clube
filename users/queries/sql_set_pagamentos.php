<?php

require_once('../../server/config.php');
include '../qrcode.php';

// Receber os dados do formulário
$ArrayDados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$id    = $ArrayDados['id'];
$nome  = $ArrayDados['nome'];
$cpf   = $ArrayDados['cpf'];
$mes   = $ArrayDados['mes'];
$ano   = $ArrayDados['ano'];
$valor = $ArrayDados['valor'];
$nome_pagamento = 'MENSALIDADE';
// Verificar se os dados foram capturados corretamente
if (empty($nome) || empty($cpf) || empty($mes) || empty($ano) || empty($valor)) {
  $retorna = ['status' => false, 'msg' => "Erro: Necessário preencher todos os campos!"];
  echo json_encode($retorna);
  exit;
}

try {
  if ($id == "new") {
    // Preparar a query de inserção
    $query_pagamentos = "INSERT INTO pagamentos (nome_pagamento, nome, cpf, mes, ano, valor, created_at, delet) VALUES (:nome_pagamento, :nome, :cpf, :mes, :ano, :valor, NOW(), '')";
    $insert_pagamentos = $pdo->prepare($query_pagamentos);
    $insert_pagamentos->bindParam(':nome_pagamento', $nome_pagamento, PDO::PARAM_STR);
    $insert_pagamentos->bindParam(':nome', $nome, PDO::PARAM_STR);
    $insert_pagamentos->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    $insert_pagamentos->bindParam(':mes', $mes, PDO::PARAM_STR);
    $insert_pagamentos->bindParam(':ano', $ano, PDO::PARAM_STR);
    $insert_pagamentos->bindParam(':valor', $valor, PDO::PARAM_STR);

    $insert_pagamentos->execute();
    if ($insert_pagamentos->rowCount() > 0) {
      $retorna = ['status' => true, 'msg' => "Dados inseridos com sucesso!"];
    } else {
      $retorna = ['status' => false, 'msg' => "Erro: Não foi possível inserir os dados!"];
    }
  } else {

    // Preparar a query de atualização
    $query_pagamentos = "UPDATE pagamentos SET nome_pagamento = :nome_pagamento, nome = :nome, cpf = :cpf, mes = :mes, ano = :ano, valor = :valor WHERE id = :id";
    $update_pagamentos = $pdo->prepare($query_pagamentos);
    $update_pagamentos->bindParam(':id', $id, PDO::PARAM_INT);
    $update_pagamentos->bindParam(':nome_pagamento', $nome_pagamento, PDO::PARAM_STR);
    $update_pagamentos->bindParam(':nome', $nome, PDO::PARAM_STR);
    $update_pagamentos->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    $update_pagamentos->bindParam(':mes', $mes, PDO::PARAM_STR);
    $update_pagamentos->bindParam(':ano', $ano, PDO::PARAM_STR);
    $update_pagamentos->bindParam(':valor', $valor, PDO::PARAM_STR);

    $update_pagamentos->execute();
    if ($update_pagamentos->rowCount() > 0) {
      $retorna = ['status' => true, 'msg' => "Dados atualizados com sucesso!"];
    } else {
      $retorna = ['status' => false, 'msg' => "Erro: Não foi possível atualizar os dados!"];
    }
  }
} catch (PDOException $e) {
  $retorna = ['status' => false, 'msg' => "Erro: " . $e->getMessage()];
}

echo json_encode($retorna);
?>