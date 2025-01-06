<?php
// require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

session_start();
if(empty($_SESSION['usuario'])) {
    header('Location: index.php');
    exit();
}

require_once('../server/config.php');

if (isset($_GET['cpf'])) {
    $cpf = htmlspecialchars(trim($_GET['cpf']));
    $stmt = $pdo->prepare("SELECT * FROM socios WHERE cpf = ?");
    $stmt->execute([$cpf]);
    $socio = $stmt->fetch();

    if (!$socio) {
        echo "Sócio não encontrado.";
        exit();
    }
} else {
    echo "CPF não fornecido.";
    exit();
}

// Configurações do Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// HTML da carteirinha
$html = '
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carteirinha Estudantil - Frente e Verso</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Arial", sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f0f0;
        }
        .card-container {
            display: flex;
            width: 1000px;
        }
        .card {
            width: 50%;
            border-radius: 12px;
            overflow: hidden;
            background-color: #fff;
            page-break-inside: avoid;
        }
        .header {
            background: linear-gradient(to right, #63b947, #4ca64d);
            color: white;
            text-align: center;
            padding: 16px;
        }
        .logo {
            height: 40px;
            margin-bottom: 8px;
        }
        .content {
            display: flex;
            padding: 16px;
            gap: 16px;
        }
        .photo img {
            width: 120px;
            height: 160px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #ddd;
        }
        .info p {
            margin-bottom: 8px;
        }
        .footer {
            background-color: #f0f0f0;
            text-align: center;
            padding: 12px;
            font-weight: bold;
            color: #333;
        }
        .back-content {
            padding: 24px;
            text-align: center;
        }
        .back-content h3 {
            margin-bottom: 12px;
            color: #4ca64d;
        }
        .qrcode {
            width: 160px;
            height: 160px;
            margin-left: 15px;
            margin: auto;
        }
        .qrcode img {
            max-width: 100%;
            max-height: 100%;
            border: 0;
        }
        @media print {
            body {
                background-color: white;
            }
            .card-container {
                width: 100%;
            }
            .card {
                width: 50%;
            }
        }
    </style>
</head>
<body>
    <div class="card-container">
        <div class="card front">
            <div class="header">
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/76/USF_logo.jpg" alt="Logo USF" class="logo">
                <h2>UNIVERSIDADE SÃO FRANCISCO</h2>
                <p>Carteira de Identificação Estudantil</p>
            </div>
            <div class="content">
                <div class="photo">
                    <img src="' . htmlspecialchars($socio['foto']) . '" alt="Foto do Sócio" class="foto-socio" />
                </div>
                <div class="info">
                    <p id="nome"><strong>Nome:</strong> ' . htmlspecialchars($socio['nome']) . '</p>
                    <p id="cpf"><strong>CPF:</strong> ' . htmlspecialchars($socio['cpf']) . '</p>
                    <p><strong>RA:</strong> 2023101234</p>
                    <p><strong>Curso:</strong> Engenharia de Software</p>
                    <p><strong>Nível do Curso:</strong> Graduação</p>
                    <p><strong>Unidade:</strong> Bragança Paulista</p>
                </div>
            </div>
        </div>
        <div class="card back">
            <div class="header">
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/76/USF_logo.jpg" alt="Logo USF" class="logo">
                <h2>UNIVERSIDADE SÃO FRANCISCO</h2>
                <p>Carteira de Identificação Estudantil</p>
            </div>
            <div class="back-content">
                <div class="qrcode">
                    <img src="qrcode/' . htmlspecialchars($socio['cpf']) . '.png" alt="QR Code" />
                </div>
            </div>
        </div>
    </div>
</body>
</html>
';

// Carrega o HTML no Dompdf
$dompdf->loadHtml($html);

// Define o tamanho do papel e a orientação
$dompdf->setPaper('A4', 'landscape');

// Renderiza o HTML como PDF
$dompdf->render();

// Envia o PDF para o navegador
$dompdf->stream('../users/carteirinha.pdf', array("Attachment" => true));
?>