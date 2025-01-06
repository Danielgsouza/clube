<?php
// Inclui o arquivo de configuração do banco de dados
require_once('../../server/config.php');

// Define o cabeçalho da resposta como JSON
header('Content-Type: application/json');
// Receber os dados JSON da requisição
$data = json_decode(file_get_contents('php://input'), true);
$year = $data['year'];

// Verificar se o ano foi capturado corretamente
if (empty($year)) {
  echo json_encode(['status' => false, 'msg' => 'Erro: Ano não fornecido!']);
  exit;
}

try {
  // Consulta SQL para buscar os dados da tabela `socios`
  $sql = "SELECT mes AS label, SUM(valor) AS value 
  FROM pagamentos 
  WHERE delet = '' 
  AND nome_pagamento = 'MENSALIDADE'
  AND ano = :year

  GROUP BY mes, ano";
 $stmt = $pdo->prepare($sql); // Prepara a consulta
 $stmt->bindParam(':year', $year, PDO::PARAM_INT); // Vincula o parâmetro do ano
 $stmt->execute(); // Executa a consulta


  // Verifica se a consulta retornou resultados
  if ($stmt->rowCount() > 0) {
    // Obtém todos os dados como um array associativo
    $socios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Converte os dados em JSON
    echo json_encode($socios);
  } else {
    // Retorna uma mensagem de erro se não houver resultados
    echo json_encode(['status' => false, 'msg' => 'Nenhum sócio encontrado.']);
  }
} catch (PDOException $e) {
  // Retorna uma mensagem de erro em caso de exceção
  echo json_encode(['status' => false, 'msg' => 'Erro: ' . $e->getMessage()]);
}
?>