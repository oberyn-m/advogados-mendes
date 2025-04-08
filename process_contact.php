<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

if (
    empty($_POST['nome']) ||
    empty($_POST['email']) ||
    empty($_POST['telefone']) ||
    empty($_POST['assunto']) ||
    empty($_POST['mensagem'])
) {
    echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios']);
    exit;
}

$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
$assunto = filter_input(INPUT_POST, 'assunto', FILTER_SANITIZE_SPECIAL_CHARS);
$mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_SPECIAL_CHARS);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'E-mail inválido']);
    exit;
}

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    
    $mail = new PHPMailer(true);
    
    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_USER'];
    $mail->Password = $_ENV['SMTP_PASS'];
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    
    $mail->setFrom($_ENV['SMTP_USER'], 'Formulário de Contato');
    $mail->addAddress($_ENV['EMAIL_TO']);
    $mail->addReplyTo($email, $nome);
    
    $mail->isHTML(true);
    $mail->Subject = 'Contato via Site: ' . $assunto;
    
    $mailBody = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            h2 { color: #1c0b2b; border-bottom: 1px solid #eee; padding-bottom: 10px; }
            .message-details { background-color: #f9f9f9; padding: 15px; border-radius: 5px; }
            .field { margin-bottom: 10px; }
            .label { font-weight: bold; color: #555; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Nova mensagem de contato</h2>
            
            <div class='message-details'>
                <div class='field'>
                    <span class='label'>Nome:</span> {$nome}
                </div>
                <div class='field'>
                    <span class='label'>E-mail:</span> {$email}
                </div>
                <div class='field'>
                    <span class='label'>Telefone:</span> {$telefone}
                </div>
                <div class='field'>
                    <span class='label'>Assunto:</span> {$assunto}
                </div>
                
                <div class='field'>
                    <span class='label'>Mensagem:</span>
                    <p>" . nl2br($mensagem) . "</p>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $mail->Body = $mailBody;
    $mail->AltBody = "Nome: $nome\nE-mail: $email\nTelefone: $telefone\nAssunto: $assunto\nMensagem: $mensagem";
    
    $mail->send();
    
    echo json_encode(['success' => true, 'message' => 'Mensagem enviada com sucesso!']);
    
} catch (Exception $e) {
    error_log('Erro ao enviar e-mail: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Não foi possível enviar sua mensagem. Por favor, tente novamente mais tarde.']);
}