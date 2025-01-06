<?php 
  session_start();
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
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/datatables.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/scrollbar.css"/>
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/slick.css"/>
    <link rel="stylesheet" type="text/css" href="../assets/css/vendors/slick-theme.css"/>
    <!-- App css -->
    <link rel="stylesheet" href="../assets/css/style.css"/>
    <link id="color" rel="stylesheet" href="../assets/css/color-1.css" media="screen"/>
     <link rel="stylesheet" href="../users/carteirinha.css">
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
                  <h2>Dependentes</h2>
                </div>
                <div class="col-sm-6 col-12">
                  <a class="btn btn-primary float-end mx-2" id="btn_dependents" href="../dependents/new-dependents.php"><i class="fa-solid fa-plus"></i> Incluir Dependente</a>
                  <!-- <a class="btn btn-secondary float-end" href="../users/associate.php"><i class="fa-solid fa-arrow-left"></i> Voltar</a> -->
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid" style="display: none;" id="div_dependents">
            <div class="edit-profile">
              <div class="row">
                <div class="col-xl-12">
                  <form id="dependents-form" class="card" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id" value="new" />
                    <input type="hidden" name="titular_id" id="titular_id" />
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-8">
                          <div class="row">
                              <div class="col-md-12">
                                <div class="mb-3">
                                  <label class="form-label">Nome Completo</label>
                                  <input class="form-control" type="text" name="nome" id="nome" placeholder="Informe o nome completo" required/>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="mb-3">
                                  <label class="form-label">CPF</label>
                                  <input class="form-control" type="text" name="cpf" id="cpf" placeholder="Informe o cpf" required/>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                  <label class="form-label">Nº Título</label>
                                  <input class="form-control" type="text" name="titulo" id="titulo" required/>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                  <label class="form-label">Data de Nascimento</label>
                                  <input class="form-control" type="date" name="data_nascimento" id="nasc" required/>
                                </div>
                              </div>
                              <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                  <label class="form-label">Telefone</label>
                                  <input class="form-control" type="text" name="telefone" id="telefone" placeholder="11999999999" required/>
                                </div>
                              </div>
                              
                              <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                  <label class="form-label">Status</label>
                                  <select class="form-select" name="status" id="status" required>
                                    <option value="1">Ativo</option>
                                    <option value="0">Inativo</option>
                                  </select>
                                </div>
                              </div>
                              
                          </div>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center">
                          <div class="mb-3 mx-auto">
                            <img id="foto-preview" alt="Foto do Sócio" class="img-thumbnail rounded-circle" style="width: 200px; height: 200px; object-fit: cover;"/>
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
                      <button class="btn btn-primary" type="submit">Salvar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive display" id="table-dependents">
                          <thead>
                            <tr>
                              <th>Status</th>
                              <th>Titular</th>
                              <th>Nome</th>
                              <th>CPF</th>
                              <th>Nº Título</th>
                              <th>Data de Nascimento</th>
                              <th>Telefone</th>
                              <th>Criado Em</th>
                              <th>Ações</th>
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

          <?php require("../layout/footer.php") ?>
        </div>
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
              <div class="card-layout front">
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
                    <p><strong>CPF:</strong> <span id="modal-cpf"></span></p>
                    <!-- <p><strong>RA:</strong> 2023101234</p>
                    <p><strong>Curso:</strong> Engenharia de Software</p>
                    <p><strong>Nível do Curso:</strong> Graduação</p>
                    <p><strong>Unidade:</strong> Bragança Paulista</p> -->
                  </div>
                </div>
              </div>

              <!-- VERSO -->
              <div class="card-layout back">
                <div class="header">
                  <img class="img-fluid for-dark" style="width: 150px;" src="../assets/images/logo/logo6.png" alt="logo" />
                  <p>Carteira de Identificação do Associado</p>
                </div>
                <div class="back-content">
                  <div class="qrcode">
                    <img id="modal-qrCode" src="" alt="QR Code" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button id="download-image" class="btn btn-primary">Baixar Carteirinha</button>
          </div>
        </div>
      </div>
    </div>
    <!--Carteirinha Modal-->

    <!--scripts-->
    <?php require("../utils/scripts.php") ?>
    <script src="../utils/js/functions.js"></script>
     
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
    <script src="./dependents.js"></script>
  </body>
</html>