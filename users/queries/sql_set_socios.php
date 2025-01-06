<?php

require_once('../../server/config.php');
include '../qrcode.php';
// Receber os dados do formulário
$ArrayDados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$id = $ArrayDados['id'];
$nome = $ArrayDados['nome'];
$cpf = $ArrayDados['cpf'];
$titulo = $ArrayDados['titulo'];
$estado_civil = $ArrayDados['estado_civil'];
$data_nascimento = $ArrayDados['data_nascimento'];
$profissao = $ArrayDados['profissao'];
$telefone = $ArrayDados['telefone'];
$endereco = $ArrayDados['endereco'];
$email = $ArrayDados['email'];
$status = (int)$ArrayDados['status']; // Converter para inteiro

// Verificar se os dados foram capturados corretamente
if (empty($nome) || empty($cpf) || empty($estado_civil) || empty($data_nascimento) || empty($telefone) || empty($endereco)) {
  $retorna = ['status' => false, 'msg' => "Erro: Necessário preencher todos os campos!"];
  echo json_encode($retorna);
  exit;
}

try {
  if ($id == "new") {
    // Preparar a query
    $query_socios = "INSERT INTO socios (nome, cpf, titulo, estado_civil, data_nascimento, profissao, telefone, endereco, email, status) VALUES (:nome, :cpf, :titulo, :estado_civil, :data_nascimento, :profissao, :telefone, :endereco, :email, :status)";
    $insert_socios = $pdo->prepare($query_socios);
    $insert_socios->bindParam(':nome', $nome, PDO::PARAM_STR);
    $insert_socios->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    $insert_socios->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $insert_socios->bindParam(':estado_civil', $estado_civil, PDO::PARAM_STR);
    $insert_socios->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
    $insert_socios->bindParam(':profissao', $profissao, PDO::PARAM_STR);
    $insert_socios->bindParam(':telefone', $telefone, PDO::PARAM_STR);
    $insert_socios->bindParam(':endereco', $endereco, PDO::PARAM_STR);
    $insert_socios->bindParam(':email', $email, PDO::PARAM_STR);
    $insert_socios->bindParam(':status', $status, PDO::PARAM_INT);
    $insert_socios->execute();
    if ($insert_socios) {
      $retorna = ['status' => true, 'msg' => "Dados inseridos com sucesso!"];
    } else {
      $retorna = ['status' => false, 'msg' => "Erro: Não foi possível inserir os dados!"];
    }
  } else {
    // Preparar a query
    $query_socios = "UPDATE socios SET nome = :nome, cpf = :cpf, titulo = :titulo, estado_civil = :estado_civil, data_nascimento = :data_nascimento, profissao = :profissao, telefone = :telefone, endereco = :endereco, email = :email, status = :status WHERE id = :id";
    $update_socios = $pdo->prepare($query_socios);
    $update_socios->bindParam(':id', $id, PDO::PARAM_INT);
    $update_socios->bindParam(':nome', $nome, PDO::PARAM_STR);
    $update_socios->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    $update_socios->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $update_socios->bindParam(':estado_civil', $estado_civil, PDO::PARAM_STR);
    $update_socios->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
    $update_socios->bindParam(':profissao', $profissao, PDO::PARAM_STR);
    $update_socios->bindParam(':telefone', $telefone, PDO::PARAM_STR);
    $update_socios->bindParam(':endereco', $endereco, PDO::PARAM_STR);
    $update_socios->bindParam(':email', $email, PDO::PARAM_STR);
    $update_socios->bindParam(':status', $status, PDO::PARAM_INT);
    $update_socios->execute();

    if ($update_socios) {
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

  // Receber os arquivos do formulário
  $arquivo = $_FILES['foto'];

  // Verificar se o arquivo foi enviado
  if ($arquivo['error'] == UPLOAD_ERR_OK) {
    // Criar o nome do arquivo usando o CPF do usuário
    $nome_arquivo = $cpf . '.' . pathinfo($arquivo['name'], PATHINFO_EXTENSION);

    // Criar o endereço de destino da imagem
    $destino = $diretorio . $nome_arquivo;
    move_uploaded_file($arquivo['tmp_name'], $destino);
    // Mover o arquivo para o diretório de destino
    // if (move_uploaded_file($arquivo['tmp_name'], $destino)) {
    //     // Salvar o caminho do arquivo e o CPF do usuário no banco de dados
    //     $query_imagem = "INSERT INTO imagens (nome_imagem, caminho_imagem, usuario_id) VALUES (:nome_imagem, :caminho_imagem, :usuario_id)";
    //     $cad_imagem = $pdo->prepare($query_imagem);
    //     $cad_imagem->bindParam(':nome_imagem', $nome_arquivo);
    //     $cad_imagem->bindParam(':caminho_imagem', $destino);
    //     $cad_imagem->bindParam(':usuario_id', $cpf);

    //     if ($cad_imagem->execute()) {
    //         $retorna['msg'] .= " Imagem enviada com sucesso!";
    //     } else {
    //         $retorna['msg'] .= " Erro ao salvar a imagem no banco de dados.";
    //     }
    // } else {
    //     $retorna['msg'] .= " Erro ao fazer upload da imagem.";
    // }
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

} catch (PDOException $e) {
  $retorna = ['status' => false, 'msg' => "Erro: " . $e->getMessage()];
}

echo json_encode($retorna);
?>