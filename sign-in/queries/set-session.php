<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $_SESSION['usuario']   = $data['usuario'];
    $_SESSION['nome']      = $data['nome'];
    $_SESSION['tipo']      = $data['tipo'];
    $_SESSION['cpf']       = $data['cpf'];
    $_SESSION['email']     = $data['email'];
    // $_SESSION['telefone']  = $data['telefone'];
    // $_SESSION['endereco']  = $data['endereco'];
    $_SESSION['status']    = $data['status'];
    $_SESSION['id']        = $data['id'];

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
?>