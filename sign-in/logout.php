<?php
  session_start();
  unset($_SESSION['usuario']);
  unset($_SESSION['nome']);
  unset($_SESSION['tipo']);
  unset($_SESSION['id']);
  unset($_SESSION['cpf']);
  unset($_SESSION['email']);
  unset($_SESSION['telefone']);
  unset($_SESSION['endereco']);
  unset($_SESSION['status']);

  header('Location: ../index.php');
  exit;
?>  
