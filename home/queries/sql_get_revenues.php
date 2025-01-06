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
  // Consulta SQL para buscar os dados da tabela `pagamentos` filtrados pelo ano
  $sql = "SELECT 
    CONCAT(ano, '-', LPAD(mes, 2, '0')) AS MES,  -- Concatena ano e mês no formato 'YYYY-MM'
    SUM(CASE WHEN tipo = 'E' THEN valor ELSE 0 END) AS ENTRADA,  -- Soma das entradas (E)
    SUM(CASE WHEN tipo = 'S' THEN valor ELSE 0 END) AS SAIDA  -- Soma das saídas (S)
  FROM 
    pagamentos 
  WHERE 
    delet = '' AND ano = :year  -- Seleciona os pagamentos não deletados e filtra pelo ano
  GROUP BY 
    ano, mes  -- Agrupa por ano e mês
  ORDER BY 
    ano, mes;";  // Ordena por ano e mês
  $stmt = $pdo->prepare($sql); // Prepara a consulta
  $stmt->bindParam(':year', $year, PDO::PARAM_INT); // Vincula o parâmetro do ano
  $stmt->execute(); // Executa a consulta

  // Verifica se a consulta retornou resultados
  if ($stmt->rowCount() > 0) {
    // Obtém todos os dados como um array associativo
    $revenues = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Converte os dados em JSON
    echo json_encode($revenues);
  } else {
    // Retorna uma mensagem de erro se não houver resultados
    echo json_encode(['status' => false, 'msg' => 'Nenhum dado encontrado para o ano fornecido.']);
  }
} catch (PDOException $e) {
  // Retorna uma mensagem de erro em caso de exceção
  echo json_encode(['status' => false, 'msg' => 'Erro: ' . $e->getMessage()]);
}
?>