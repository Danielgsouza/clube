<?php
session_start();
require_once('../server/config.php');
include 'qrcode.php';
if (!isset($_SESSION['nome'])) {
  header("Location: ../index.php");
  exit();
}
$username = $_SESSION['nome'];
// Verificar tipo da chamada (Navbar ou Sidebar)
$type = isset($_POST['type']) ? $_POST['type'] : "";

if ($type === 'navbar') {
  $nome = $_SESSION['nome'];
  $user = $_SESSION['usuario'];
  $tipo = $_SESSION['tipo'];
  $cpf = $_SESSION['cpf'];
  $email = $_SESSION['email'];
  $status = $_SESSION['status'];
  $id = $_SESSION['id'];
} else{
  $nome = "";
  $user = "";
  $tipo = "";
  $cpf = "";
  $email = "";
  $status = "";
  $id = "";
}
?>

<script>
  var type = "<?php echo htmlspecialchars($type); ?>";
  var nome = "<?php echo htmlspecialchars($nome); ?>";
  var user = "<?php echo htmlspecialchars($user); ?>";
  var tipo = "<?php echo htmlspecialchars($tipo); ?>";
  var cpf = "<?php echo htmlspecialchars($cpf); ?>";
  var email = "<?php echo htmlspecialchars($email); ?>";
  var status = "<?php echo htmlspecialchars($status); ?>";
  var id = "<?php echo htmlspecialchars($id); ?>";
 </script>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="Admiro admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities."/>
    <meta name="keywords" content="admin template, Admiro admin template, best javascript admin, dashboard template, bootstrap admin template, responsive admin template, web app"/>
    <meta name="author" content="pixelstrap"/>
    <title>Clube de Campo Morungabense</title>
    <!-- Favicon icon-->
    <link rel="icon" href="../assets/images/logo/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/images/logo/favicon.png" type="image/x-icon">
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""/>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,200;6..12,300;6..12,400;6..12,500;6..12,600;6..12,700;6..12,800;6..12,900;6..12,1000&amp;display=swap" rel="stylesheet"/>
    <!-- Flag icon css -->
    <link rel="stylesheet" href="../assets/css/vendors/flag-icon.css"/>
    <!-- iconly-icon-->
    <link rel="stylesheet" href="../assets/css/iconly-icon.css"/>
    <link rel="stylesheet" href="../assets/css/bulk-style.css"/>
    <!-- iconly-icon-->
    <link rel="stylesheet" href="../assets/css/themify.css"/>
    <!--fontawesome-->
    <link rel="stylesheet" href="../assets/css/fontawesome-min.css"/>
    <!-- Whether Icon css-->
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/weather-icons/weather-icons.min.css"/>
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/scrollbar.css"/>
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/slick.css"/>
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/slick-theme.css"/>
    <!-- App css -->
    <link rel="stylesheet" href="../assets/css/style.css"/>
    <link id="color" rel="stylesheet" href="../assets/css/color-1.css" media="screen"/>
    <link rel="stylesheet" href="../assets/css/notyf.min.css">
  </head>
  <body>

    <!-- page-wrapper Start-->
    <!-- tap on top starts-->
    <div class="tap-top"><i class="iconly-Arrow-Up icli"></i></div>
    <!-- tap on tap ends-->
    <!-- loader-->
    <div class="loader-wrapper">
      <div class="loader"><span></span><span></span><span></span><span></span><span></span></div>
    </div>
    <?php require("../layout/navbar.php") ?>
    <div class="page-wrapper compact-wrapper" id="pageWrapper"> 
    <?php require("../layout/sidebar.php") ?>
      <!-- Page Body Start-->
      <div class="page-body-wrapper"> 
        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-sm-6 col-12"> 
                  <h2>Usuários</h2>
                </div>
                <div class="col-sm-6 col-12">
                  <a class="btn btn-primary float-end" href="../users/users.php"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="edit-profile">
              <div class="row">
                <div class="col-xl-12">
                  <form id="user-form" class="card" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" />
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-8">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="mb-3">
                                <label class="form-label">Nome Completo</label>
                                <input class="form-control" type="text" name="nome" placeholder="Informe o nome completo" required/>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="mb-3">
                                <label class="form-label">CPF</label>
                                <input class="form-control" type="text" name="cpf" placeholder="Informe o cpf" required/>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="mb-3">
                                <label class="form-label">Usuário</label>
                                <input class="form-control" type="text" name="user" placeholder="Informe a profissão" required/>
                              </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                              <div class="mb-3">
                                <label class="form-label">Tipo</label>
                                <select class="form-select" name="userType" required>
                                  <option value="administrador">Administrador</option>
                                  <option value="usuario">Usuário</option>
                                </select>
                              </div>
                            </div>
                            
                            <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input class="form-control" type="email" name="email" placeholder="Informe o nome completo" required/>
                              </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                              <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                  <option value="1">Ativo</option>
                                  <option value="0">Inativo</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group" id="div-password">
                              <label class="col-form-label">Senha</label>
                              <div class="form-input position-relative">
                                <input class="form-control bg-white" type="password" name="password" id="password" required="" placeholder="*********">
                                  <div class="show-hide"><span class="show"></span></div>
                                </input>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center">
                          <div class="mb-3 mx-auto">
                            <img id="foto-preview" alt="Foto do Sócio" src="../assets/images/avatar/icon.png" class="img-thumbnail rounded-circle" style="width: 200px; height: 200px; object-fit: cover;"/>
                          </div>
                          <!-- <input type="file" id="foto" name="foto" accept="image/*" style="display: none;" onchange="previewImage(event)"/> -->
                          <div class="mb-3 mx-auto">
                            <button type="button" class="btn btn-primary" id="upload-button">Selecionar Foto</button>
                            <input type="file" id="foto" name="foto" accept="image/*" style="display: none;" onchange="previewImage(event)"/>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer text-end">
                      <button class="btn btn-primary" type="submit" id="btn-submit">Salvar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php require("../layout/footer.php") ?>
      </div>
    </div>
    <!--scripts-->
    <script src="../utils/js/functions.js"></script>
    <?php require("../utils/scripts.php")?>
   <script src="./new-user.js"></script>
   <script src="../assets/js/password.js"></script>
</body>
</html>