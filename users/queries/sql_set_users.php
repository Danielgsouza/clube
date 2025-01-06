<?php

require_once('../../server/config.php');
include '../qrcode.php';

// Receber os dados do formulário
$ArrayDados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$id = $ArrayDados['id'];
$nome = $ArrayDados['nome'];
$cpf = $ArrayDados['cpf'];
$usuario = $ArrayDados['user'];
$tipo = $ArrayDados['userType'];
$email = $ArrayDados['email'];
$status = (int)$ArrayDados['status']; // Converter para inteiro
$senha = password_hash(trim($ArrayDados['password']), PASSWORD_DEFAULT); // Adicionando senha

// Verificar se os dados foram capturados corretamente
if (empty($nome) || empty($cpf) || empty($usuario) || empty($tipo) || empty($email) || empty($senha)) {
  $retorna = ['status' => false, 'msg' => "Erro: Necessário preencher todos os campos!"];
  echo json_encode($retorna);
  exit;
}

try {
  if ($id == "new") {
    // Preparar a query
    $query_users = "INSERT INTO usuarios (cpf, nome, usuario, email, senha, tipo, created_at, status) VALUES (:cpf, :nome, :usuario, :email, :senha, :tipo, NOW(), :status)";
    $insert_users = $pdo->prepare($query_users);
    $insert_users->bindParam(':nome', $nome, PDO::PARAM_STR);
    $insert_users->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $insert_users->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    $insert_users->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    $insert_users->bindParam(':email', $email, PDO::PARAM_STR);
    $insert_users->bindParam(':senha', $senha, PDO::PARAM_STR);
    $insert_users->bindParam(':status', $status, PDO::PARAM_INT);
    $insert_users->execute();
    if ($insert_users) {
      $retorna = ['status' => true, 'msg' => "Dados inseridos com sucesso!"];
    } else {
      $retorna = ['status' => false, 'msg' => "Erro: Não foi possível inserir os dados!"];
    }
  } else {
    // Preparar a query
    $query_users = "UPDATE usuarios SET cpf = :cpf, nome = :nome, usuario = :usuario, email = :email, senha = :senha, tipo = :tipo, modified_at = NOW(), status = :status WHERE id = :id";
    $update_users = $pdo->prepare($query_users);
    $update_users->bindParam(':id', $id, PDO::PARAM_INT);
    $update_users->bindParam(':nome', $nome, PDO::PARAM_STR);
    $update_users->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $update_users->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    $update_users->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    $update_users->bindParam(':email', $email, PDO::PARAM_STR);
    $update_users->bindParam(':senha', $senha, PDO::PARAM_STR);
    $update_users->bindParam(':status', $status, PDO::PARAM_INT);
    $update_users->execute();

    if ($update_users) {
      $retorna = ['status' => true, 'msg' => "Dados atualizados com sucesso!"];
    } else {
      $retorna = ['status' => false, 'msg' => "Erro: Não foi possível atualizar os dados!"];
    }
  }

  // Endereço do diretório
  $diretorio = "../../users/uploads/";

  // Criar o diretório se não existir
  if (!is_dir($diretorio)) {
    mkdir($diretorio, 0755, true);
  }

  // Verificar se já existe uma imagem associada ao CPF do usuário
  $imagemExistente = glob($diretorio . $cpf . '.*'); // Procurar qualquer arquivo com o CPF como nome, de qualquer extensão

  // Receber os arquivos do formulário
  $arquivo = $_FILES['foto'];

  // Verificar se o arquivo foi enviado
  if ($arquivo['error'] == UPLOAD_ERR_OK) {
    // Validar tipo de arquivo (exemplo para imagens JPEG ou PNG)
    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
    if (!in_array($extensao, ['jpg', 'jpeg', 'png'])) {
      $retorna = ['status' => false, 'msg' => "Erro: O arquivo deve ser uma imagem JPG ou PNG!"];
      echo json_encode($retorna);
      exit;
    }

    // Se já existir uma imagem associada ao CPF, excluir a imagem antiga
    if (!empty($imagemExistente)) {
      // Excluir a imagem antiga
      $imagemAntiga = $imagemExistente[0];
      if (!unlink($imagemAntiga)) {
        $retorna = ['status' => false, 'msg' => "Erro: Não foi possível excluir a imagem existente."];
        echo json_encode($retorna);
        exit;
      }
    }

    // Criar o nome do arquivo usando o CPF do usuário
    $nome_arquivo = $cpf . '.' . $extensao;

    // Criar o endereço de destino da imagem
    $destino = $diretorio . $nome_arquivo;

    // Mover o arquivo para o diretório de destino
    if (move_uploaded_file($arquivo['tmp_name'], $destino)) {
      $retorna = ['status' => true, 'msg' => "Imagem atualizada com sucesso!"];
      echo json_encode($retorna);
      exit;
    } else {
      $retorna = ['status' => false, 'msg' => "Erro: Não foi possível mover a imagem para o diretório de destino."];
      echo json_encode($retorna);
      exit;
    }
  } else {
    // Caso nenhum arquivo tenha sido enviado (erro de upload ou nenhum arquivo)
    if (empty($imagemExistente)) {
      $retorna = ['status' => false, 'msg' => "Erro: Nenhuma imagem foi enviada e não há imagem existente para manter."];
      echo json_encode($retorna);
      exit;
    }
    
    // Caso o upload não tenha ocorrido, mas a imagem já exista, mantém a imagem existente
    $retorna = ['status' => true, 'msg' => "Nenhuma nova imagem foi enviada. A imagem existente foi mantida."];
    echo json_encode($retorna);
    exit;
  }

} catch (PDOException $e) {
  $retorna = ['status' => false, 'msg' => "Erro: " . $e->getMessage()];
}

echo json_encode($retorna);
?>