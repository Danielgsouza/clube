<?php
// Inclui o arquivo de configuração do banco de dados
require_once('../../server/config.php');

// Define o cabeçalho da resposta como JSON
header('Content-Type: application/json');

try {
    // Consulta SQL para buscar os dados da tabela `usuarios`
    $sql = "SELECT id, cpf, nome, usuario, email, status, tipo, DATE_FORMAT(created_at, '%d/%m/%Y %H:%i:%s') as createdAt, CASE WHEN modified_at IS NULL THEN '' ELSE DATE_FORMAT(modified_at, '%d/%m/%Y %H:%i:%s') END as modifiedAt FROM usuarios";
    $stmt = $pdo->query($sql); // Executa a consulta

    // Verifica se a consulta retornou resultados
    if ($stmt->rowCount() > 0) {
      // Obtém todos os dados como um array associativo
      $socios = $stmt->fetchAll(PDO::FETCH_ASSOC);
      // Converte os dados em JSON
      echo json_encode($socios);
    } else {
      // Retorna uma mensagem de erro se não houver resultados
      echo json_encode(['status' => false, 'msg' => 'Nenhum usuário encontrado.']);
    }
} catch (PDOException $e) {
    // Retorna uma mensagem de erro em caso de exceção
    echo json_encode(['status' => false, 'msg' => 'Erro: ' . $e->getMessage()]);
}
?>