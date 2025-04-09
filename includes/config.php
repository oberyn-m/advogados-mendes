<?php
// Configurações de sessão (devem vir antes de qualquer session_start)
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', 3600); // 1 hora

// Configurações de segurança
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Carrega as variáveis de ambiente do arquivo .env
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Configurações do site
$siteName = 'Mendes Advocacia';
$siteDescription = 'Excelência em Direito Trabalhista';
$siteEmail = 'contato@mendesadvocacia.com.br';
$sitePhone = '(11) 1234-5678';
$siteAddress = 'Av. Paulista, 1000 - São Paulo, SP';
$workingHours = 'Segunda a Sexta: 9h às 18h';
$currentYear = date('Y');

// Configurações WhatsApp
$numberWhatsapp = "5573982581380"; // Número do WhatsApp (apenas números)
$messageWhatsapp = "Olá! Gostaria de agendar uma consulta."; // Mensagem padrão

// Links Redes Sociais
$linkFacebook = "https://facebook.com/mendesadvocacia";
$linkInstagram = "https://instagram.com/mendesadvocacia";
$linkLinkedin = "https://linkedin.com/company/mendesadvocacia";

// Configurações de timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de erro - apenas em ambiente de desenvolvimento
if (getenv('APP_ENV') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../logs/error.log');
}

// Gera token CSRF para formulários
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Função para validar token CSRF
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Função para sanitizar entrada de dados
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Função para validar email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
} 