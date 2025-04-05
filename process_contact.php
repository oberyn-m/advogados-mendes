<?php
// Importar o PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Carregar o autoloader do Composer
require 'vendor/autoload.php';

// Verificar se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obter os dados do formulário
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
    $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_STRING);
    
    // Validar os dados
    if (empty($nome) || empty($email) || empty($mensagem)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos obrigatórios.']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Por favor, forneça um email válido.']);
        exit;
    }
    
    // Configurar o PHPMailer
    $mail = new PHPMailer(true);
    
    try {
        // Carregar configurações do .env
        $env = parse_ini_file('.env');
        
        // Configurações do servidor (Gmail)
        $mail->isSMTP();
        $mail->Host = $env['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $env['SMTP_USERNAME'];
        $mail->Password = $env['SMTP_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $env['SMTP_PORT'];
        $mail->CharSet = 'UTF-8';
        
        // Destinatários
        $mail->setFrom('isaachavester@gmail.com', 'Teste Formulário');
        $mail->addAddress('isaachavester@gmail.com', 'Teste Mendes'); // Email que receberá as mensagens
        $mail->addReplyTo($email, $nome);
        
        // Conteúdo
        $mail->isHTML(true);
        $mail->Subject = 'Nova mensagem do site - Mendes Advocacia';
        
        // Corpo do email
        $mail->Body = "
            <h2>Nova mensagem do formulário de contato</h2>
            <p><strong>Nome:</strong> {$nome}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Telefone:</strong> {$telefone}</p>
            <p><strong>Mensagem:</strong></p>
            <p>{$mensagem}</p>
        ";
        
        $mail->AltBody = "
            Nova mensagem do formulário de contato\n
            Nome: {$nome}\n
            Email: {$email}\n
            Telefone: {$telefone}\n
            Mensagem: {$mensagem}
        ";
        
        // Enviar o email
        $mail->send();
        
        // Resposta de sucesso - Garantir que headers e encoding estejam corretos
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'message' => 'Mensagem enviada com sucesso!'
        ], JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (Exception $e) {
        // Resposta de erro - Garantir que headers e encoding estejam corretos
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao enviar mensagem: ' . $mail->ErrorInfo
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
} else {
    // Modificar a linha que redireciona para a página inicial
    header('Location: index.php');
    exit;
}