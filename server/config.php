<?php
// Configurações do banco de dados
$host = 'localhost'; // ou o seu host
$db = 'clube'; // seu banco de dados
$user = 'root'; // seu usuário do banco de dados
$pass = ''; // sua senha do banco de dados

// Conexão com o banco de dados
try {
  $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo 'Conexão falhou: ' . $e->getMessage();
  exit();
}
