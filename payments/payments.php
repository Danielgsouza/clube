<?php
session_start();
if (!isset($_SESSION['nome'])) {
  header("Location: ../index.php");
  exit();
}
$username = $_SESSION['nome'];
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="Admiro admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities."/>
    <meta name="keywords" content="admin template, Admiro admin template, best javascript admin, dashboard template, bootstrap admin template, responsive admin template, web app"/>
    <meta name="author" content="pixelstrap"/>
    <title>CC - Clube de Campo Morungabense</title>
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
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/datatables.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/scrollbar.css"/>
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/slick.css"/>
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/slick-theme.css"/>
    <!-- App css -->
    <link rel="stylesheet" href="../assets/css/style.css"/>
    <link id="color" rel="stylesheet" href="../assets/css/color-1.css" media="screen"/>
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
                  <h2>Caixa</h2>
                </div>
                <div class="col-sm-6 col-12">
                  <a class="btn btn-primary float-end mx-2" id="btn_payments" href="new-payments.php"><i class="fa-solid fa-plus"></i> Incluir Lançamento</a>
                  <!-- <a class="btn btn-secondary float-end" href="../users/associate.php"><i class="fa-solid fa-arrow-left"></i> Voltar</a> -->
                </div>
              </div>
            </div>
          </div>
          <div class="container-fluid tab-bootstrap-page">
            <div class="row">
              <div class="col-xxl-12 mb-lg-3"> 
                <div class="card">
                  <div class="card-body">
                    <ul class="nav nav-tabs border-tab border-0 mb-0" id="topline-tab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active nav-border pt-0 text-success nav-success" id="inputOption" data-bs-toggle="tab" href="#topline-top-user" role="tab" aria-controls="topline-top-user" aria-selected="true">    
                        <i data-feather="chevron-up"></i>Entrada
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link nav-border text-danger nav-danger" id="outputOption" data-bs-toggle="tab" href="#topline-top-description" role="tab" aria-controls="topline-top-description" aria-selected="false">
                        <i data-feather="chevron-down"></i>Saída   
                        </a>
                      </li>
                    </ul>
                    <div class="tab-content" id="topline-tabContent">
                      <div class="tab-pane fade show active" id="topline-top-user" role="tabpanel" aria-labelledby="inputOption">
                        <div class="card-body px-0 pb-0"> 
                          <!-- <div class="user-header pb-2"> 
                            <h6 class="fw-bold">User Details:</h6>
                          </div> -->
                          <div class="user-content">  
                            <div class="table table-responsive">
                              <table class="table table-responsive mb-0" id="table-input">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">NOME</th>
                                    <!-- <th scope="col">CPF</th> -->
                                    <th scope="col">MÊS</th>
                                    <th scope="col">ANO</th>
                                    <th scope="col">VALOR</th>
                                    <th scope="col">DATA</th>
                                    <th scope="col">AÇÕES</th>
                                  </tr>
                                </thead>
                                <tbody id="tbodyInput">
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="topline-top-description" role="tabpanel" aria-labelledby="outputOption">
                        <div class="card-body px-0 pb-0">
                          <div class="user-content"> 
                            <div class="table table-responsive">
                              <table class="table mb-0" id="table-output">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">NOME</th>
                                    <!-- <th scope="col">CPF</th> -->
                                    <th scope="col">MÊS</th>
                                    <th scope="col">ANO</th>
                                    <th scope="col">VALOR</th>
                                    <th scope="col">DATA</th>
                                    <th scope="col">AÇÕES</th>
                                  </tr>
                                </thead>
                                <tbody id="tbodyOutput">
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
            </div>
          </div>
          
        </div>
        <?php require("../layout/footer.php") ?>
      </div>
    </div>
    <!--scripts-->
    <?php require("./../utils/scripts.php")?>
    <script src="../utils/js/functions.js"></script>
    <script src="./payments.js"></script>
  </body>
</html>