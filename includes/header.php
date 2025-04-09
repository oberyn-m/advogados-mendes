<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mendes Advocacia - Especialistas em Direito do Trabalho</title>
  <meta name="description" content="Escritório especializado em direito do trabalho, oferecendo serviços de consultoria e representação jurídica para trabalhadores e empresas.">
  
  <!-- Cache Control -->
  <meta http-equiv="Cache-Control" content="public, max-age=31536000">
  
  <!-- Favicon -->
  <link rel="icon" href="./img/favicon.ico" type="image/x-icon">
  
  <!-- Preload recursos críticos -->
  <link rel="preload" href="./css/main.css" as="style">
  <link rel="preload" href="./js/main.js" as="script">
  <link rel="preload" href="./img/lawyer.jpg" as="image">
  
  <!-- Preconnect para recursos externos -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://cdnjs.cloudflare.com">
  
  <!-- Fontes com loading otimizado -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
  
  <!-- CSS principal com loading otimizado -->
  <link rel="stylesheet" href="./css/main.css" media="print" onload="this.media='all'">
  <noscript><link rel="stylesheet" href="./css/main.css"></noscript>
  
  <!-- Ícones com loading otimizado -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
  
  <!-- Meta tags para SEO -->
  <meta name="keywords" content="advogado trabalhista, direito do trabalho, advocacia, consultoria jurídica, Mendes Advocacia">
  <meta name="author" content="Mendes Advocacia">
  <meta name="robots" content="index, follow">
  
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://advogadosmendes.com.br/">
  <meta property="og:title" content="Mendes Advocacia - Especialistas em Direito do Trabalho">
  <meta property="og:description" content="Escritório especializado em direito do trabalho, oferecendo serviços de consultoria e representação jurídica para trabalhadores e empresas.">
  <meta property="og:image" content="img/og-image.jpg">
  
  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="https://advogadosmendes.com.br/">
  <meta property="twitter:title" content="Mendes Advocacia - Especialistas em Direito do Trabalho">
  <meta property="twitter:description" content="Escritório especializado em direito do trabalho, oferecendo serviços de consultoria e representação jurídica para trabalhadores e empresas.">
  <meta property="twitter:image" content="img/og-image.jpg">
  
  <!-- CSRF Token para formulários -->
  <meta name="csrf-token" content="<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">
  
  <!-- Fallback para recursos externos -->
  <noscript>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
  </noscript>
  
  <!-- Registro do Service Worker -->
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('/advogados-mendes/js/sw.js')
          .then(registration => {
            // ServiceWorker registrado com sucesso
          })
          .catch(error => {
            // Falha ao registrar o ServiceWorker
          });
      });
    }
  </script>
</head>
<body>
  <header class="header">
    <div class="container">
      <div class="header-content">
        <a href="/advogados-mendes/" class="logo">Mendes Advocacia</a>
        <button class="menu-toggle" aria-label="Menu" aria-expanded="false" aria-controls="navbar-collapse">
          <span class="hamburger"></span>
        </button>
        <nav class="nav">
          <ul class="menu">
            <li class="menu-item"><a href="#inicio" class="menu-link">Início</a></li>
            <li class="menu-item"><a href="#sobre" class="menu-link">Sobre</a></li>
            <li class="menu-item"><a href="#areas" class="menu-link">Áreas</a></li>
            <li class="menu-item"><a href="#artigos" class="menu-link">Artigos</a></li>
            <li class="menu-item"><a href="#faq" class="menu-link">FAQ</a></li>
            <li class="menu-item"><a href="#contato" class="menu-link">Contato</a></li>
          </ul>
        </nav>
      </div>
    </div>
  </header>
  
  <main>