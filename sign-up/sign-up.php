<?php
// Conexão com o banco de dados
require_once('../server/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitização e validação dos dados
    $nome = trim($_POST['nome']);
    $usuario = trim($_POST['usuario']);
    $email = trim($_POST['email']);
    $senha = password_hash(trim($_POST['senha']), PASSWORD_DEFAULT);
    $tipo = trim($_POST['tipo']);

    // Verifica se os campos obrigatórios estão preenchidos
    if (empty($nome) || empty($usuario) || empty($email) || empty($senha) || empty($tipo)) {
      die("Todos os campos são obrigatórios.");
    }

    // Prepara a consulta
    $sql = "INSERT INTO usuarios (nome, usuario, email, senha, tipo, created_at) VALUES (:nome, :usuario, :email, :senha, :tipo, NOW())";
    
    $stmt = $pdo->prepare($sql);
    
    // Vincula os parâmetros
    $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);

    // Executa a consulta e verifica se foi bem-sucedida
    if ($stmt->execute()) {
        echo "Novo usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar o usuário.";
    }
}

// Fechar a conexão
$pdo = null;
?>


<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admiro admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Admiro admin template, best javascript admin, dashboard template, bootstrap admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <title>Clube de Campo Morungabense</title>
    <!-- Favicon icon-->
    <link rel="icon" href="../assets/images/logo/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/images/logo/favicon.png" type="image/x-icon">
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,200;6..12,300;6..12,400;6..12,500;6..12,600;6..12,700;6..12,800;6..12,900;6..12,1000&amp;display=swap" rel="stylesheet">
    <!-- Flag icon css -->
    <link rel="stylesheet" href="../assets/css/vendors/flag-icon.css">
    <!-- iconly-icon-->
    <link rel="stylesheet" href="../assets/css/iconly-icon.css">
    <link rel="stylesheet" href="../assets/css/bulk-style.css">
    <!-- iconly-icon-->
    <link rel="stylesheet" href="../assets/css/themify.css">
    <!--fontawesome-->
    <link rel="stylesheet" href="../assets/css/fontawesome-min.css">
    <!-- Whether Icon css-->
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/weather-icons/weather-icons.min.css">
    <!-- App css -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link id="color" rel="stylesheet" href="../assets/css/color-1.css" media="screen">
  </head>
  <body>
    <!-- tap on top starts-->
    <div class="tap-top"><i class="iconly-Arrow-Up icli"></i></div>
    <!-- tap on tap ends-->
    <!-- loader-->
    <div class="loader-wrapper">
      <div class="loader"><span></span><span></span><span></span><span></span><span></span></div>
    </div>
    <!-- login page start-->
    <div class="container-fluid p-0">
      <div class="row m-0">
        <div class="col-12 p-0">    
          <div class="login-card login-dark">
            <div>
              <div>
                <a class="logo text-center" href="index.html">
                  <img class="img-fluid for-light m-auto" src="../assets/images/logo/logo1.png" alt="looginpage"> 
                  <img class="img-fluid for-dark" style="width: 200px;"src="../assets/images/logo/logo6.png" alt="logo" />
                </a>
              <div class="login-main"> 
                <form class="theme-form" method="post">
                  <h2 class="text-center">Crie sua Conta</h2>
                  <p class="text-center">Informe os dados pessoais para criar uma conta</p>
                  <div class="form-group">
                    <label class="col-form-label pt-0">Nome completo</label>
                    <div class="row g-2">
                      <div class="col-12">
                        <input class="form-control" type="text" name="nome" required="" placeholder="Informe seu nome completo ">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Usuário</label>
                    <input class="form-control" type="text" name="usuario" required="" placeholder="Informe o nome de usuário">
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Email</label>
                    <div class="form-input position-relative">
                      <input class="form-control" type="email" name="email" required="" placeholder="Informe o email da conta">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Senha</label>
                    <div class="form-input position-relative">
                      <input class="form-control" type="password" name="login[password]" required="" placeholder="*********">
                      <div class="show-hide"><span class="show"></span></div>
                    </div>
                  </div>
                   <div class="form-group">
                    <label class="col-form-label">Tipo de Usuário</label>
                    <div class="form-input position-relative">
                      <select class="form-control" name="tipo" required="">
                        <option value="1">Administrador</option>
                        <option value="2">Usuário</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group mb-0 checkbox-checked">
                    <div class="form-check checkbox-solid-info">
                      <input class="form-check-input" id="solid6" type="checkbox">
                      <label class="form-check-label" for="solid6">Concordo com</label><a class="ms-3 link" href="../sign-in/forget-password.html">Politica de Privacidade</a>
                    </div>
                    <button class="btn btn-primary btn-block w-100 mt-3" type="submit">Criar Conta</button>
                  </div>
                
                  <p class="mt-4 mb-0 text-center">Já possui uma conta?<a class="ms-2" href="./../index.php">Sign in</a></p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="../assets/js/password.js"></script>
    <?php require('../utils/scripts.php') ?>
  </body>
</html>