<?php
session_start();
require_once('../server/config.php');

$usuario = $_POST['usuario'];
$senha = $_POST['password'];

// Prepara a consulta
$sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_OBJ);

if ($row && password_verify($senha, $row->senha)) {
    // Senha correta
    $sessionData = [
        'usuario'   => $row->usuario,
        'nome'      => $row->nome,
        'tipo'      => $row->tipo,
        'cpf'       => $row->cpf,
        'email'     => $row->email,
        'status'    => $row->status,
        'id'        => $row->id
    ];
    ?>
    <script>
        const sessionData = <?php echo json_encode($sessionData); ?>;
        fetch('./queries/set-session.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(sessionData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.href = '../home/home.php';
            } else {
                console.error('Failed to set session:', data.message);
                window.location.href = '../index.php';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.location.href = '../index.php';
        });
    </script>
    <?php
    exit();
} else {
    // Senha incorreta ou usuário não encontrado
    $_SESSION['login_error'] = "Usuário ou senha incorretos!";
    header('Location: ../index.php');
    exit();
}
?>