<?php 
require_once __DIR__ . '/../config.php';

// Verifica se há mensagens para exibir
$message = null;
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    // Limpa a mensagem da sessão para que não seja exibida novamente
    unset($_SESSION['message']);
}
?>

<section class="section contact-section"> 
  <div class="container">
    <div class="section-header">
      <h2>Entre em Contato</h2>
      <p>Estamos prontos para ajudar você</p>
    </div>
    
    <?php if ($message): ?>
    <div class="alert alert-<?php echo $message['type']; ?>">
      <?php echo $message['text']; ?>
    </div>
    <?php endif; ?>
    
    <div class="contact-container">
      <div class="contact-form-container">
        <form id="contactForm" class="contact-form" action="process_contact.php" method="POST">
          <div class="form-group">
            <label for="nome">Nome Completo <span class="char-count" id="nomeCount">0/200</span></label>
            <input type="text" id="nome" name="nome" required maxlength="200" />
            <span class="error-message" id="nomeError"></span>
          </div>
          <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required placeholder="seu@email.com" />
            <span class="error-message" id="emailError"></span>
          </div>
          <div class="form-group">
            <label for="telefone">Telefone</label>
            <input type="tel" id="telefone" name="telefone" required placeholder="(XX) XXXXX-XXXX ou (XX) XXXX-XXXX" />
            <span class="error-message" id="telefoneError"></span>
          </div>
          <div class="form-group">
            <label for="assunto">Assunto <span class="char-count" id="assuntoCount">0/200</span></label>
            <input type="text" id="assunto" name="assunto" required maxlength="200" />
            <span class="error-message" id="assuntoError"></span>
          </div>
          <div class="form-group">
            <label for="mensagem">Mensagem <span class="char-count" id="mensagemCount">0/1000</span></label>
            <textarea id="mensagem" name="mensagem" rows="5" required maxlength="1000"></textarea>
            <span class="error-message" id="mensagemError"></span>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">Enviar</button>
          </div>
        </form>
      </div>
      <div class="contact-info">
        <div class="info-container">
          <h3>Informações de Contato</h3>
          <p><i class="icon-location"></i> <?php echo $siteAddress; ?></p>
          <p><i class="icon-phone"></i> <?php echo $sitePhone; ?></p>
          <p>
            <i class="icon-email"></i>
            <?php echo $siteEmail; ?>
          </p>
          <h4>Horário de Atendimento</h4>
          <p>Segunda a Sexta: 9h às 18h<br>Sábado: 9h às 13h</p>
        </div>
        <div class="map-container">
          <h3>Localização</h3>
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3657.098839582944!2d-46.652496384406414!3d-23.564611167596294!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ce59c8da0aa315%3A0x63b9f0e6074baa47!2sAv.%20Paulista%2C%201000%20-%20Bela%20Vista%2C%20S%C3%A3o%20Paulo%20-%20SP%2C%2001310-100!5e0!3m2!1spt-BR!2sbr!4v1650000000000!5m2!1spt-BR!2sbr"
            width="100%"
            height="300"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer"
            title="Localização do escritório"
            onerror="this.outerHTML='<div class=\'map-fallback\'><p>Mapa não disponível. <a href=\'https://goo.gl/maps/1JjYxGsXCDPmUJYL6\' target=\'_blank\' rel=\'noopener\'>Ver no Google Maps</a></p></div>'">
          </iframe>
        </div>
      </div>
    </div>
  </div>
</section>