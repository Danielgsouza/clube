<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

$statusMessage = '';
$statusType = ''; // Pode ser 'success' ou 'error'

require_once('../server/config.php'); // Conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['email']) || empty($_POST['email'])) {
        $statusMessage = "O campo de email é obrigatório.";
        $statusType = 'error';
    } else {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $statusMessage = "Por favor, forneça um email válido.";
            $statusType = 'error';
        } else {
            $stmt = $pdo->prepare("SELECT id, email, nome FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $statusMessage = "Este email não está registrado em nossa base de dados.";
                $statusType = 'error';
            } else {
                $token = bin2hex(random_bytes(16));
                $expires = time() + 3600;

                $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires) VALUES (:email, :token, :expires)");
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                $stmt->bindParam(':expires', $expires, PDO::PARAM_INT);
                $stmt->execute();

                try {
                    $phpmailer = new PHPMailer();
                    $phpmailer->isSMTP();
                    // $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
                    $phpmailer->Host = 'smtp.hostinger.com';
                    $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;   
                    $phpmailer->SMTPAuth = true;
                    $phpmailer->Port = 465;
                    // $phpmailer->Username = '2080509af80695';
                    $phpmailer->Username = 'adminccm@adminccm.com';
                    // $phpmailer->Password = 'd2e1132044d48c';
                    $phpmailer->Password = 'Adminccm-2025';
                    $phpmailer->setFrom('adminccm@adminccm.com', 'Recuperação de Senha');
                    $phpmailer->addAddress($email);

                    $phpmailer->isHTML(true);
                    $phpmailer->CharSet = 'UTF-8';
                    $phpmailer->Subject = 'Recuperação de Senha';
                    $phpmailer->Body = '
                    <!DOCTYPE html>
                    <html lang="pt-br">
                    <head>
                      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                      <meta name="viewport" content="width=device-width, initial-scale=1.0">
                      <title>Recuperação de Senha</title>
                      <style>
                        body {
                          font-family: "Arial", sans-serif;
                          background-color: #F9FAFC;
                          padding: 20px;
                        }
                        .button {
                          padding: 10px 20px;
                          background-color: #308e87;
                          color: #fff;
                          text-decoration: none;
                          border-radius: 30px;
                        }
                      </style>
                    </head>
                    <body style="margin: 30px auto;">
                      <table style="width: 100%">
                        <tbody>
                          <tr>
                            <td>
                              <table style="background-color: #F9FAFC; width: 100%">
                                <tbody>
                                  <tr>
                                    <td>
                                      <table style="margin: 0 auto; margin-bottom: 30px">
                                        <tbody>
                                          <tr class="logo-sec" style="display: flex; align-items: center; justify-content: space-between; width: 650px;">
                                            <td>  
                                         
                                             <img class="img-fluid for-dark" style="width: 200px;" src="http://localhost/clube/assets/images/logo/logo2.png" alt="logo" />
                                            </td>
                                            <td style="text-align: right; color:#999"><span>Redefina a senha</span></td>
                                          </tr>
                                        </tbody>
                                      </table>
                                      <table style=" margin: 0 auto; background-color: #fff; border-radius: 8px">
                                        <tbody>
                                          <tr>
                                            <td style="padding: 30px"> 
                                              <p>Olá, ' . htmlspecialchars($user['nome']) . '!</p>
                                              <p>Você está recebendo este e-mail porque nós recebemos uma solicitação de redefinição de senha para sua conta.</p>
                                              <div class="text-center"><a href="http://localhost/clube/sign-in/new-password.php?token=' . $token . '" style="padding: 20px 20px; background-color: #1b9dff; color: #fff; display: inline-block; border-radius:30px; margin-bottom:18px; font-weight:600; text-decoration:none">REDEFINIR SENHA</a></div>
                                              <p>Se você não nos enviou esta solicitação, ignore este e-mail.</p>
                                              <p style="margin-bottom: 0">Abraços, </p> <p>Equipe CC Morungabense.</p>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                      <table style=" margin: 0 auto; margin-top: 30px">
                                        <tbody>       
                                          <tr style="text-align: center">
                                            <td> 
                                              <p style="color: #999; margin-bottom: 0">R. Elvira Laurentes Tobias, 23-101, Morungaba - SP, 13260-000</p>
                                              <p style="color: #999; margin-bottom: 0">Desenvolvido por Daniel Souza</p>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </body>
                    </html>';

                    $phpmailer->send();
                    $statusMessage = "Um email foi enviado com instruções para recuperar sua senha.";
                    $statusType = 'success';
                } catch (Exception $e) {
                    $statusMessage = "Erro ao enviar o email: " . $phpmailer->ErrorInfo;
                    $statusType = 'error';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="page-wrapper">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
                    <div class="login-card login-dark">
                        <div>
                            <a class="logo" href="./../index.php">
                                <img class="img-fluid for-light m-auto" src="../assets/images/logo/logo1.png" alt="logo">
                            </a>
                            <div class="login-main"> 
                                <form class="theme-form" method="post">
                                    <h2>Recuperar Senha</h2>
                                    <div class="form-group">
                                        <label class="col-form-label">Email</label>
                                        <input class="form-control" type="email" name="email" required placeholder="Informe o email cadastrado">
                                    </div>
                                    <div class="form-group mb-0 checkbox-checked">
                                        <button class="btn btn-primary btn-block w-100 mt-3" type="submit">Enviar</button>
                                    </div>
                                    <p class="mt-4 mb-0">Já possui uma conta?<a class="ms-2" href="./../index.php">Fazer Login</a></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exibição do SweetAlert -->
    <?php if (!empty($statusMessage)): ?>
    <script>
        Swal.fire({
            title: '<?= $statusType === "success" ? "Sucesso!" : "Erro!" ?>',
            text: '<?= $statusMessage ?>',
            icon: '<?= $statusType ?>',
            confirmButtonText: 'OK'
        });
    </script>
    <?php endif; ?>
</body>
</html>
