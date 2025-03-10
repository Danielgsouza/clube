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

  // Endereço do diretório onde as imagens serão armazenadas
  $diretorio = "../../users/uploads/";

  // Criar o diretório se não existir
  if (!is_dir($diretorio)) {
    mkdir($diretorio, 0755, true);
  }

  // Verificar se o CPF foi enviado
  if (isset($_POST['cpf'])) {
    // Obtém o CPF do POST
    $cpf = $_POST['cpf'];
    
    // Verificar se existe uma imagem associada ao CPF do usuário
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
      // if (empty($imagemExistente)) {
      //   $retorna = ['status' => false, 'msg' => "Erro: Nenhuma imagem foi enviada e não há imagem existente para manter."];
      //   echo json_encode($retorna);
      //   exit;
      // }
      
      // Caso o upload não tenha ocorrido, mas a imagem já exista, mantém a imagem existente
      echo json_encode(['status' => true, 'msg' => "Nenhuma nova imagem foi enviada. A imagem existente foi mantida."]);
      exit;
    }

    // Geração do QR Code
    $name = $cpf . ".png"; // Define o nome do arquivo do QR Code
    $file = "../qrcode/{$name}"; // Define o caminho para salvar o QR Code

    // Verifica se a pasta qrcode existe e cria se não existir
    if (!is_dir('../qrcode/')) {
      mkdir('../qrcode/', 0755, true);
    }

    $options = array(
      'w' => 400,
      'h' => 400,
    );

    $generator = new QRCode($cpf, $options); // Gerador de QR Code usando o CPF
    $image = $generator->render_image();
    
    // Salva a imagem do QR Code na pasta qrcode
    imagepng($image, $file);
    imagedestroy($image);

    // Define o caminho do QR Code para exibição
    $qrCodeFileName = $file;
    
    // Exibir a mensagem de sucesso
    echo json_encode(['status' => true, 'msg' => "QR Code gerado com sucesso!", 'qrCode' => $qrCodeFileName]);
    exit;
  }


} catch (PDOException $e) {
  $retorna = ['status' => false, 'msg' => "Erro: " . $e->getMessage()];
}

echo json_encode($retorna);
?>