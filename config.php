<?php
// Configurações de sessão (devem vir antes de qualquer session_start)
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));

// Carrega as variáveis de ambiente do arquivo .env
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Configurações do site
$siteName = 'Mendes Advocacia';
$siteDescription = 'Excelência em Direito Trabalhista';
$siteEmail = $_ENV['SMTP_USERNAME'] ?? 'contato@mendesadvocacia.com.br';
$sitePhone = '(11) 1234-5678';
$siteAddress = 'Av. Paulista, 1000 - São Paulo, SP';

// Configurações de timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de erro
error_reporting(E_ALL);
ini_set('display_errors', 1); 