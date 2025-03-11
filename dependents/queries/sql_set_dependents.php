<?php

require_once('../../server/config.php');
include '../../users/qrcode.php';

// Receber os dados do formulário
$ArrayDados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$id = $ArrayDados['id'];
$nome = $ArrayDados['nome'];
$cpf = $ArrayDados['cpf'];
$data_nascimento = $ArrayDados['data_nascimento'];
$titulo = $ArrayDados['titulo'];
$telefone = $ArrayDados['telefone'];
$titular_id = $ArrayDados['titular_id'];
$status = (int)$ArrayDados['status']; // Converter para inteiro

// Verificar se os dados foram capturados corretamente
if (empty($nome) || empty($cpf) || empty($data_nascimento) || empty($telefone) || empty($titular_id)) {
  $retorna = ['status' => false, 'msg' => "Erro: Necessário preencher todos os campos!"];
  echo json_encode($retorna);
  exit;
}

try {
  if ($id == "new") {
    // Preparar a query
    $query_dependents = "INSERT INTO dependentes (nome, cpf, data_nascimento, titulo, telefone, titular_id, status, created_at) VALUES (:nome, :cpf, :data_nascimento, :titulo, :telefone, :titular_id, :status, NOW())";
    $insert_socios = $pdo->prepare($query_dependents);
    $insert_socios->bindParam(':nome', $nome, PDO::PARAM_STR);
    $insert_socios->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    $insert_socios->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
    $insert_socios->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $insert_socios->bindParam(':telefone', $telefone, PDO::PARAM_STR);
    $insert_socios->bindParam(':titular_id', $titular_id, PDO::PARAM_STR);
    $insert_socios->bindParam(':status', $status, PDO::PARAM_INT);
    $insert_socios->execute();
    if ($insert_socios->rowCount() > 0) {
      $retorna = ['status' => true, 'msg' => "Dados inseridos com sucesso!"];
    } else {
      $retorna = ['status' => false, 'msg' => "Erro: Não foi possível inserir os dados!"];
    }
  } else {
    // Preparar a query
    $query_dependents = "UPDATE dependentes SET nome = :nome, cpf = :cpf, data_nascimento = :data_nascimento, titulo = :titulo, telefone = :telefone, titular_id = :titular_id, status = :status, modified_at = NOW() WHERE id = :id";
    $update_socios = $pdo->prepare($query_dependents);
    $update_socios->bindParam(':id', $id, PDO::PARAM_INT);
    $update_socios->bindParam(':nome', $nome, PDO::PARAM_STR);
    $update_socios->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    $update_socios->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
    $update_socios->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $update_socios->bindParam(':telefone', $telefone, PDO::PARAM_STR);
    $update_socios->bindParam(':titular_id', $titular_id, PDO::PARAM_STR);
    $update_socios->bindParam(':status', $status, PDO::PARAM_INT);
    $update_socios->execute();

    if ($update_socios->rowCount() > 0) {
      $retorna = ['status' => true, 'msg' => "Dados atualizados com sucesso!"];
    } else {
      $retorna = ['status' => false, 'msg' => "Erro: Não foi possível atualizar os dados!"];
    }
  }

  if(isset($_POST['cpf'])){
    // Obtém o CPF
    $text = $_POST['cpf'];
    
    // Define o nome do arquivo como o CPF
    $name = $cpf . ".png";
    
    // Define o caminho para salvar o QR Code
    $file = "../qrcode/{$name}";

    // Verifica se a pasta qrcode existe e cria se não existir
    if (!is_dir('../qrcode/')) {
      mkdir('../qrcode/', 0755, true);
    }

    $options = array(
      'w' => 200,
      'h' => 200,
    );
    $generator = new QRCode($text, $options);
    $image = $generator->render_image();
    
    // Salva a imagem do QR Code na pasta qrcode
    imagepng($image, $file);
    imagedestroy($image);

    // Define o caminho do QR Code para exibição
    $qrCodeFileName = $file; // Salva o caminho do arquivo
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
    // $retorna = ['status' => true, 'msg' => "Nenhuma nova imagem foi enviada. A imagem existente foi mantida."];
    echo json_encode($retorna);
    exit;
  }


} catch (PDOException $e) {
  $retorna = ['status' => false, 'msg' => "Erro: " . $e->getMessage()];
}

echo json_encode($retorna);
?>