<?php
require_once 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Define o cabeçalho para JSON
header('Content-Type: application/json');

// Função para retornar resposta JSON
function sendJsonResponse($success, $message) {
    echo json_encode([
        'success' => $success,
        'message' => $message
    ]);
    exit;
}

// Verifica se é uma requisição POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se todos os campos obrigatórios foram preenchidos
    if (
        empty($_POST['nome']) ||
        empty($_POST['email']) ||
        empty($_POST['telefone']) ||
        empty($_POST['assunto']) ||
        empty($_POST['mensagem'])
    ) {
        sendJsonResponse(false, 'Todos os campos são obrigatórios');
    }

    // Sanitiza os dados de entrada
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
    $assunto = filter_input(INPUT_POST, 'assunto', FILTER_SANITIZE_SPECIAL_CHARS);
    $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_SPECIAL_CHARS);

    // Valida o e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendJsonResponse(false, 'E-mail inválido');
    }

    try {
        // Carrega as variáveis de ambiente
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        
        // Configura o PHPMailer
        $mail = new PHPMailer(true);
        
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USERNAME'];
        $mail->Password = $_ENV['SMTP_PASSWORD'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $_ENV['SMTP_PORT'];
        $mail->CharSet = 'UTF-8';
        
        $mail->setFrom($_ENV['SMTP_USERNAME'], 'Formulário de Contato');
        $mail->addAddress($_ENV['SMTP_USERNAME']); // Enviando para o mesmo e-mail
        $mail->addReplyTo($email, $nome);
        
        $mail->isHTML(true);
        $mail->Subject = 'Contato via Site: ' . $assunto;
        
        // Template do e-mail
        $mailBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #1c0b2b; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Novo Contato via Site</h2>
                </div>
                <div class='content'>
                    <p><strong>Nome:</strong> {$nome}</p>
                    <p><strong>E-mail:</strong> {$email}</p>
                    <p><strong>Telefone:</strong> {$telefone}</p>
                    <p><strong>Assunto:</strong> {$assunto}</p>
                    <p><strong>Mensagem:</strong></p>
                    <p>{$mensagem}</p>
                </div>
                <div class='footer'>
                    <p>Este e-mail foi enviado através do formulário de contato do site.</p>
                </div>
            </div>
        </body>
        </html>";
        
        $mail->Body = $mailBody;
        $mail->AltBody = strip_tags($mailBody);
        
        // Envia o e-mail
        $mail->send();
        
        // Retorna sucesso
        sendJsonResponse(true, 'Mensagem enviada com sucesso! Entraremos em contato em breve.');
        
    } catch (Exception $e) {
        // Registra o erro no log
        error_log("Erro ao enviar e-mail: " . $e->getMessage());
        
        // Retorna erro
        sendJsonResponse(false, 'Não foi possível enviar sua mensagem. Por favor, tente novamente mais tarde.');
    }
} else {
    sendJsonResponse(false, 'Método de requisição inválido');
}