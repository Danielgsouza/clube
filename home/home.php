<?php 
  session_start();
  $username = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Usuário';
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
    <div class=" page-wrapper compact-wrapper " id="pageWrapper"> 
      
      <?php require("../layout/navbar.php")?>
      <!-- Page Body Start-->
      <div class="page-body-wrapper"> 
        <!-- Page sidebar start-->
        <?php
          require("../layout/sidebar.php")
        ?>

        <!-- Page sidebar end-->
        <div class="page-body">
          <!-- Container-fluid starts-->
          <div class="container-fluid general-widget">
            <div class="row">
              <div class="col-xxl-12 col-xl-7 box-col-12 mt-3">
                <div class="row g-sm-3 height-equal-2 widget-charts">

                  <div class="col-sm-4">
                    <div class="card small-widget mb-sm-0">
                      <div class="card-body primary"><span class="f-light">Bem-vindo,</span>
                        <div class="d-flex align-items-end gap-1">
                          <h4><?php echo htmlspecialchars($username); ?></h4><span class="font-primary order-chartf-12 f-w-500">
                        </div>
                        <div class="bg-gradient">
                          <svg class="stroke-icon svg-fill">
                            <use href="../assets/svg/iconly-sprite.svg#Home-dashboard"></use>
                          </svg>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="card small-widget mb-sm-0">
                      <div class="card-body success"><span class="f-light">Entrada</span>
                        <div class="d-flex align-items-end gap-1">
                          <h4 id="entry"></h4>
                          <!-- <span class="font-success order-chartf-12 f-w-500">
                            <i class="icon-arrow-up"></i>
                            <span id="percentage-entry"></span>
                          </span> -->
                        </div>
                        <div class="bg-gradient">
                          <svg class="stroke-icon svg-fill">
                            <use href="../assets/svg/icon-sprite.svg#profit"></use>
                          </svg>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="card small-widget mb-sm-0">
                      <div class="card-body secondary"><span class="f-light">Saída</span>
                        <div class="d-flex align-items-end gap-1">
                          <h4 id="outflow"></h4>
                          <!-- <span class="font-secondary f-12 f-w-500">
                            <i class="icon-arrow-down"></i>
                            <span id="percentage-exit"></span>
                          </span> -->
                        </div>
                        <div class="bg-gradient">
                          <svg class="stroke-icon svg-fill">
                            <use href="../assets/svg/icon-sprite.svg#profit"></use>
                          </svg>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid Ends-->
          <div class="container-fluid ">
            <div class="page-title">
            
              <div class="row">
                <div class="col-sm-12 col-xl-6 box-col-12">
                  <div class="card">
                    <div class="card-header card-no-border pb-0">
                      <h3>Sócios</h3>
                    </div>
                    <div class="card-body apex-chart">
                    <div id="associateChart"></div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12 col-xl-6 box-col-12">
                  <div class="card">
                    <div class="card-header card-no-border pb-0">
                      <h3>Dependentes</h3>
                    </div>
                    <div class="card-body apex-chart">
                    <div id="dependentsChart"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12 proorder-xl-6 box-col-12">
                  <div class="card growthcard">
                    <div class="card-header card-no-border pb-0 d-flex">
                      <!-- <h3>Pagamentos (Mensalidades)</h3> -->
                      <div class="col-11">
                        <h3>Pagamentos (Mensalidades)</h3>
                      </div>
                      <div class="col-1">
                        <select class="justify-content-evenly" id="year">
                          <?php
                          $currentYear = date("Y");
                          for ($year = $currentYear; $year >= $currentYear - 10; $year--) {
                            echo "<option value=\"$year\" $selected>$year</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="card-body pb-0">
                      <div id="growth-chart"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12 proorder-xl-6 box-col-12">
                  <div class="card growthcard">
                    <div class="card-header card-no-border pb-0 d-flex">
                      <div class="col-11">
                        <h3>Receitas</h3>
                      </div>
                      <div class="col-1">
                        <select class="justify-content-evenly" id="year-select">
                          <?php
                          $currentYear = date("Y");
                          for ($year = $currentYear; $year >= $currentYear - 10; $year--) {
                            echo "<option value=\"$year\" $selected>$year</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="card-body pb-0">
                      <div id="revenue-chart"></div>
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
    <?php require("../utils/scripts.php")?>
    <script src="./home.js"></script>
    <script src="../assets/js/chart/apex-chart/apex-chart.js"></script>
    <script src="../assets/js/chart/apex-chart/apex-chart.js"></script>
    <script src="../assets/js/chart/apex-chart/stock-prices.js"></script>


   <!-- <script src="../assets/js/vendors/jquery/jquery.min.js"></script> -->
    <!-- <script src="../assets/js/vendors/bootstrap/dist/js/bootstrap.bundle.min.js" defer=""></script>
    <script src="../assets/js/vendors/bootstrap/dist/js/popper.min.js" defer=""></script>
    <script src="../assets/js/vendors/font-awesome/fontawesome-min.js"></script>
    <script src="../assets/js/config.js"></script>
    <script src="../assets/js/vendors/swiper/swiper-bundle.min.js"></script> -->
    <!-- <script src="../assets/js/widget/general-widget.js"></script> -->
    <!-- <script src="../assets/js/script.js"></script> -->

    <!-- dashboard_1-->
    <!-- <script src="../assets/js/dashboard/dashboard_1.js"></script> -->

    <!-- Morris.js and Raphael.js -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script> -->
  </body>
</html>
