<?php
require_once __DIR__ . '/includes/config.php';
session_start();

// Verificação de ambiente local mais precisa
$isLocalhost = (
    preg_match('/^localhost(:81)?$/', $_SERVER['HTTP_HOST']) || 
    preg_match('/^127\.0\.0\.1(:81)?$/', $_SERVER['HTTP_HOST'])
);

// Forçar HTTPS apenas em produção
if (!$isLocalhost && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on')) {
    $redirectUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: " . $redirectUrl);
    exit();
}

// Headers de segurança (exceto HSTS em localhost)
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

// Desativa HSTS em ambiente local
if ($isLocalhost) {
    header("Strict-Transport-Security: max-age=0");
} else {
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
}

require 'includes/header.php';
?>

<section class="hero" id="inicio">
  <div class="hero-content">
    <h1>Mendes Advocacia<br><span>Excelência em Direito Trabalhista</span></h1>
    <button class="scroll-down" aria-label="Rolar para próxima seção">
      <i class="fas fa-chevron-down"></i>
    </button>
  </div>
</section>

<section id="sobre" class="about">    
  <div class="container">
    <div class="section-header">
      <h2>Sobre Nós</h2>
      <p>Conheça nossa história e compromisso com a justiça trabalhista</p>
    </div>
    <div class="about-content">
      <div class="about-text">
        <p>Com mais de 15 anos de experiência, a Mendes Advocacia se destaca pela excelência no atendimento e pela defesa incansável dos direitos trabalhistas.</p>
        <p>Nossa equipe é formada por profissionais altamente qualificados, comprometidos em oferecer soluções jurídicas personalizadas para cada cliente.</p>
        <p>Acreditamos que o acesso à justiça é um direito fundamental, e trabalhamos diariamente para torná-lo uma realidade para nossos clientes.</p>
      </div>
      <div class="about-image">
        <img src="img/lawyer.jpg" alt="Equipe jurídica" loading="lazy" width="600" height="400">
      </div>
    </div>
  </div>
</section>

<section id="areas" class="services">
  <div class="container">
    <div class="section-header">
      <h2>Nossos Serviços</h2>
      <p>Oferecemos assistência jurídica especializada em diversas áreas do Direito do Trabalho</p>
    </div>
    <div class="services-grid">
      <div class="service-card">
        <h3>Reclamações Trabalhistas</h3>
        <p>Representamos trabalhadores em processos contra empregadores, buscando o reconhecimento de direitos e o pagamento de verbas devidas.</p>
      </div>
      <div class="service-card">
        <h3>Acordos Trabalhistas</h3>
        <p>Mediamos negociações entre empregados e empregadores, buscando soluções que atendam aos interesses de ambas as partes.</p>
      </div>
      <div class="service-card">
        <h3>Consultoria Empresarial</h3>
        <p>Orientamos empresas sobre a correta aplicação da legislação trabalhista, prevenindo conflitos e reduzindo riscos jurídicos.</p>
      </div>
      <div class="service-card">
        <h3>Análise de Contratos</h3>
        <p>Avaliamos contratos de trabalho, termos de rescisão e outros documentos, garantindo a conformidade com a legislação vigente.</p>
      </div>
      <div class="service-card">
        <h3>Negociações Coletivas</h3>
        <p>Assessoramos sindicatos e empresas em negociações coletivas, buscando acordos que beneficiem todas as partes envolvidas.</p>
      </div>
      <div class="service-card">
        <h3>Defesa em Processos</h3>
        <p>Defendemos empresas em processos trabalhistas, desenvolvendo estratégias eficazes para minimizar impactos financeiros.</p>
      </div>
    </div>
  </div>
</section>

<section id="artigos" class="articles">
  <div class="container">
    <div class="section-header">
      <h2>Artigos e Notícias</h2>
      <p>Fique por dentro das novidades e atualizações do mundo jurídico trabalhista</p>
    </div>
    <div id="articlesContainer" class="articles-grid">
    </div>
    <div id="articleModalsContainer" class="article-modals">
    </div>
  </div>
</section>

<section id="faq" class="faq">
  <div class="container">
    <div class="section-header">
      <h2>Perguntas Frequentes</h2>
      <p>Respostas para as dúvidas mais comuns sobre direitos trabalhistas</p>
    </div>
    <div class="faq-container">
      <div class="faq-item">
        <div class="faq-question">
          <h3>Qual o prazo para entrar com uma ação trabalhista?</h3>
          <button class="faq-toggle">+</button>
        </div>
        <div class="faq-answer">
          <p>O prazo prescricional para ajuizamento de ação trabalhista é de 5 anos durante a vigência do contrato de trabalho, até o limite de 2 anos após a extinção do contrato. Isso significa que você pode reclamar direitos dos últimos 5 anos, mas deve fazer isso em até 2 anos após o término do seu contrato de trabalho.</p>
        </div>
      </div>
      <div class="faq-item">
        <div class="faq-question">
          <h3>Quais documentos são necessários para uma consulta inicial?</h3>
          <button class="faq-toggle">+</button>
        </div>
        <div class="faq-answer">
          <p>Para uma consulta inicial, é recomendável trazer: carteira de trabalho, contrato de trabalho, holerites, termo de rescisão (se houver), comprovantes de horas extras, e qualquer outro documento relacionado à relação de trabalho que possa ser relevante para o seu caso.</p>
        </div>
      </div>
      <div class="faq-item">
        <div class="faq-question">
          <h3>Quanto tempo dura um processo trabalhista?</h3>
          <button class="faq-toggle">+</button>
        </div>
        <div class="faq-answer">
          <p>A duração de um processo trabalhista varia conforme a complexidade do caso, a região e a carga de trabalho do tribunal. Em média, pode levar de 1 a 3 anos para ser concluído. Casos mais simples podem ser resolvidos em menos tempo, especialmente quando há possibilidade de acordo.</p>
        </div>
      </div>
      <div class="faq-item">
        <div class="faq-question">
          <h3>Quais são meus direitos em caso de demissão sem justa causa?</h3>
          <button class="faq-toggle">+</button>
        </div>
        <div class="faq-answer">
          <p>Em caso de demissão sem justa causa, o trabalhador tem direito a: aviso prévio (trabalhado ou indenizado), saldo de salário, férias vencidas e proporcionais acrescidas de 1/3, 13º salário proporcional, liberação do FGTS com multa de 40% e seguro-desemprego (se atender aos requisitos).</p>
        </div>
      </div>
      <div class="faq-item">
        <div class="faq-question">
          <h3>É possível fazer acordo em um processo trabalhista?</h3>
          <button class="faq-toggle">+</button>
        </div>
        <div class="faq-answer">
          <p>Sim, é possível e até incentivado pela Justiça do Trabalho. O acordo pode ser realizado em qualquer fase do processo, inclusive antes mesmo de sua instauração. A conciliação é uma forma eficiente de resolver o conflito, economizando tempo e recursos para ambas as partes.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="contato" class="section contact-section">
  <?php include 'includes/contact-form.php'; ?>
</section>
</main>

<?php include 'includes/footer.php'; ?>