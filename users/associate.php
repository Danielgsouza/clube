<?php 
  session_start();
  if (!isset($_SESSION['nome'])) {
    header("Location: ../index.php");
    exit();
  }
  $username = $_SESSION['nome'];
?>
<!DOCTYPE html >
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
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/datatables.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/slick.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/slick-theme.css">
    <!-- App css -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link id="color" rel="stylesheet" href="../assets/css/color-1.css" media="screen">
    <link rel="stylesheet" href="./carteirinha.css">
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
        <!-- Page sidebar start-->
        <!-- Page sidebar end-->
        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-sm-6 col-12"> 
                  <h2>Sócios</h2>
                </div>
                <div class="col-sm-6 col-12">
                  <a class="btn btn-primary float-end" href="new-associate.php"><i class="fa-solid fa-plus"></i> Novo Sócio</a><a class="icon" href="new-associate.php"></a>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="edit-profile">
              <div class="row">
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-responsive display" id="table-socios">
                        <thead>
                          <tr>
                            <th>STATUS</th>
                            <th>CPF</th>
                            <th>NOME</th>
                            <!-- <th>ESTADO CIVIL</th> -->
                            <th>PROFISSÃO</th>
                            <th>DATA DE NASC.</th>
                            <th>TELEFONE</th>
                            <th>ENDEREÇO</th>
                            <!-- <th>EMAIL</th> -->
                            <th>AÇÕES</th>
                          </tr>
                        </thead>
                        <tbody id="tbody">
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              </div>
            </div>
          </div>
        </div>
        <?php require("../layout/footer.php") ?>
      </div>
    </div>
     
    <!--Carteirinha Modal-->
    <div class="modal fade bd-example-modal-xl"  id="carteirinhaModal" tabindex="-1" role="dialog" aria-labelledby="carteirinhaModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title fs-5" id="carteirinhaModalLabel">Visualizar Carteirinha</h3>
            <button class="btn-close" type="button" data-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="card-container" id="card-container">
              <!-- FRENTE -->
              <div class="card-layout front" id="card-front">
                <div class="header">
                  <img class="img-fluid for-dark" style="width: 150px;" src="../assets/images/logo/logo6.png" alt="logo" />
                  <p>Carteira de Identificação do Associado</p>
                </div>
                <div class="content">
                  <div class="photo">
                    <img id="foto-preview" src="../assets/images/avatar/icon.png" alt="Foto do Sócio" class="foto-socio" />
                  </div>
                  <div class="info">
                    <p><strong>Nome:</strong> <span id="modal-nome"></span></p>
                    <p><strong>Título:</strong> <span id="modal-titulo"></span></p>
                    <p><strong>CPF:</strong> <span id="modal-cpf"></span></p>
                    <p><strong>Tipo:</strong> <span></span>Titular</p>
                  </div>
                  <div class="qrcode d-none" id="qrCodeFront">
                    <img id="modal-qrCode-front" src="" alt="QR Code" />
                  </div>
                </div>
              </div>

              <!-- VERSO -->
              <div class="card-layout back" id="card-back">
                <div class="header">
                  <img class="img-fluid for-dark" style="width: 150px;" src="../assets/images/logo/logo6.png" alt="logo" />
                  <p>Carteira de Identificação do Associado</p>
                </div>
                <div class="back-content d-none" id="qrCodeBack">
                  <div class="qrcode">
                    <img id="modal-qrCode-back" src="" alt="QR Code" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="mx-auto">
              <button id="btn-fisico" class="btn btn-info">Fisica</button>
              <button id="btn-digital" class="btn btn-info">Digital</button>
              <!-- <button id="btn-digital" class="btn btn-info">QR Code</button> -->
            </div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button id="download-image" class="btn btn-primary">Baixar Carteirinha</button>
          </div>
        </div>
      </div>
    </div>
    <!--Carteirinha Modal-->
                    
    <!--scripts-->
    <?php require("../utils/scripts.php")?>
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
    <script src="../utils/js/functions.js"></script>
    <script src="./associate.js"></script>
    
  </body>
</html>

<!-- <li class='delete'><a href='#'><i class='icon-trash'></i></a></li> -->
 