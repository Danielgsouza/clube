<!DOCTYPE html >
<html lang="pt-br">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="Admiro admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities."/>
    <meta name="keywords" content="admin template, Admiro admin template, best javascript admin, dashboard template, bootstrap admin template, responsive admin template, web app"/>
    <meta name="author" content="pixelstrap"/>
    <title>Clube de Campo Morungabensee</title>
    <!-- Favicon icon-->
    <link rel="icon" href="../assets/images/logo/favicon.png" type="image/x-icon">
    <!--<link rel="shortcut icon" href="../../assets/images/logo/favicon.png" type="image/x-icon">-->
    
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
    <link rel="stylesheet" href="../assets/css/notyf.min.css">

    <style>
      .hidden-input {
        position: absolute;
        left: -9999px;
      }
    </style>
  </head>
  <body>

    <!-- tap on top starts-->
    <div class="tap-top"><i class="iconly-Arrow-Up icli"></i></div>
    <!-- tap on tap ends-->

    <!-- loader-->
    <div class="loader-wrapper">
      <div class="loader"><span></span><span></span><span></span><span></span><span></span></div>
    </div>
 
    <div class="page-wrapper compact-wrapper" id="pageWrapper"> 
      <!-- Page Body Start-->
      <!--<div class="page-body-wrapper"> -->
        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="d-flex">
                <!-- <a class="logo" href="index.php"> -->
                  <!-- <img class="img-fluid for-light m-auto" src="../assets/images/logo/logo1.png" alt="looginpage" /> -->
                  <img class="img-fluid for-dark mx-auto" style="width: 200px;"src="../assets/images/logo/logo7.png" alt="logo" />
                <!-- </a> -->
              </div>
              <div class="row my-3">
                <div class="col-sm-12 col-12 text-center text-bold"> 
                  <h1>Controle de Acesso</h1>
                </div>
                <div class="card" style="border: 2px solid #000">
                <form id="access-control-form" class="card" method="post" enctype="multipart/form-data">
                  <input type="text" class="hidden-input" id="socio-id" name="socio-id"/>
                </form>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-8">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="mb-3">
                                <label class="form-label" style="font-size: 1.5rem;">Nome Completo</label>
                                <div class="form-control-plaintext">
                                  <span id="nome" style="font-size: 1.5rem;"></span>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="mb-3">
                                <label class="form-label" style="font-size: 1.5rem;">CPF</label>
                                <div class="form-control-plaintext">
                                  <span id="cpf" style="font-size: 1.5rem;"></span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                              <div class="mb-3">
                                <label class="form-label" style="font-size: 1.5rem;">Status</label>
                                <div class="form-control-plaintext">
                                  <span id="status" style="font-size: 1.5rem;"></span>
                                </div>
                              </div>
                            </div>

                            <!-- <div class="col-sm-6 col-md-12 d-flex">
                              <div class="mb-3 mx-auto">
                                <label class="form-label">Status</label>
                                <div class="form-control-plaintext">
                                  <span class="text-center" id="status-icon" style="font-size: 1.5rem;"></span>
                                </div>
                              </div>
                            </div> -->
                          </div>
                      </div>
                      <div class="col-md-4 d-flex flex-column align-items-center">
                          <div class="mb-3 mx-auto">
                              <img id="foto-preview" src="../assets/images/avatar/icon.png" alt="Foto do Sócio" class="img-thumbnail rounded-circle" style="width: 200px; height: 200px; object-fit: cover;"/>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>               
              </div>
          
            </div>
          </div>
          <!-- Container-fluid starts-->
        </div>
      <!--</div>-->
    </div>
    <!--scripts-->
    <?php require("../utils/scripts.php")?>
    <script src="./../assets/js/notyf/notyf.min.js"></script>
    <script src="./access-control.js"></script>
  </body>
</html>
