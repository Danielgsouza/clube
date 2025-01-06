<?php
    // session_start();
    // require_once('../server/config.php');
    // if(empty($_SESSION['usuario'])) {
    //     header('Location: ./home.php');
    //     exit;
    // }

    // Verifica se a variável $_SESSION['cpf'] está definida
    $cpf = isset($_SESSION['cpf']) ? $_SESSION['cpf'] : null;
    $tipo = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : null;
    $nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Usuário';
    $imagePath = "../assets/images/avatar/icon.png"; // Caminho da imagem padrão
?>

<header class="page-header row">
    <div class="logo-wrapper d-flex align-items-center col-auto">
        <a href="./clube/home/home.php">
            <img class="light-logo img-fluid" src="../assets/images/logo/logo2.png" alt="logo" />
        </a>
        <a class="close-btn toggle-sidebar" href="javascript:void(0)" id="toggleSidebarLink">
            <svg class="svg-color">
                <use href="../assets/svg/iconly-sprite.svg#Category"></use>
            </svg>
        </a>
    </div>
    <div class="page-main-header col">
        <div class="header-left">
            <form class="form-inline search-full col" action="#" method="get">
                <div class="form-group w-100">
                    <div class="Typeahead Typeahead--twitterUsers">
                        <div class="u-posRelative">
                            <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="Search Admiro .." name="q" title="" autofocus="autofocus" />
                            <div class="spinner-border Typeahead-spinner" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <i class="close-search" data-feather="x"></i>
                        </div>
                        <div class="Typeahead-menu"></div>
                    </div>
                </div>
            </form>
        </div>
        <div class="nav-right">
            <ul class="header-right">
                <li class="profile-nav custom-dropdown">
                    <div class="user-wrap">
                        <div class="user-img bg-transparent">
                            <!-- A imagem será carregada dinamicamente via JavaScript -->
                            <img id="user-image" src="" alt="user" />
                        </div>
                        <div class="user-content">
                            <h6 id="user-name"><?php echo $nome; ?></h6>
                            <p class="mb-0"><?php echo $tipo; ?><i class="fa-solid fa-chevron-down"></i></p>
                        </div>
                    </div>
                    <div class="custom-menu overflow-hidden">
                        <ul class="profile-body">
                            <li class="d-flex">
                                <svg class="svg-color">
                                    <use href="../assets/svg/iconly-sprite.svg#Profile"></use>
                                </svg>
                                <form action="../users/new-user.php" method="post" style="display: inline;">
                                    <input type="hidden" name="type" value="navbar">
                                    <button type="submit" class="bn btn-transparent bg-white border-0">Conta</button>
                                </form>
                            </li>
                            <li class="d-flex">
                                <svg class="svg-color">
                                    <use href="../assets/svg/iconly-sprite.svg#Login"></use>
                                </svg>
                                <a class="ms-2" href="../sign-in/logout.php">Sair</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>

<script>
// Carregar a imagem dinamicamente via JavaScript
document.addEventListener("DOMContentLoaded", function() {
    // Obter o CPF e nome da sessão PHP (passados como variáveis JavaScript)
    var cpf = "<?php echo $cpf; ?>"; // Recebendo CPF da sessão PHP
    var nome = "<?php echo $nome; ?>"; // Recebendo nome da sessão PHP

    // Definir o caminho da imagem, caso o CPF exista
    var imagePath = cpf ? "../users/uploads/" + cpf + ".jpg" : "../assets/images/avatar/icon.png";

    // Definir o nome do usuário
    document.getElementById('user-name').textContent = nome;

    // Atribuir a imagem ao elemento <img> com id 'user-image'
    document.getElementById('user-image').src = imagePath;

      // Obter o link que vai disparar a ação
      var toggleSidebarLink = document.getElementById('toggleSidebarLink');
    
    // Obter a div do pageWrapper
    var pageWrapper = document.getElementById('pageWrapper');
    
    // Adicionar o ouvinte de evento para o clique
    toggleSidebarLink.addEventListener('click', function() {
        // Alternar a classe sidebar-open
        pageWrapper.classList.toggle('sidebar-open');
    });

});
</script>
