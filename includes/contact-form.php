<?php 
require_once 'config.php';
?>

<section class="section contact-section"> 
  <div class="container">
    <div class="section-header">
      <h2>Entre em Contato</h2>
      <p>Estamos prontos para ajudar você</p>
    </div>
    <div class="contact-container">
      <div class="contact-form-container">
        <form id="contactForm" class="contact-form" action="process_contact.php" method="POST">
          <div class="form-group">
            <label for="nome">Nome Completo</label>
            <input type="text" id="nome" name="nome" required />
          </div>
          <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required />
          </div>
          <div class="form-group">
            <label for="telefone">Telefone</label>
            <input type="tel" id="telefone" name="telefone" required />
          </div>
          <div class="form-group">
            <label for="assunto">Assunto</label>
            <input type="text" id="assunto" name="assunto" required />
          </div>
          <div class="form-group">
            <label for="mensagem">Mensagem</label>
            <textarea id="mensagem" name="mensagem" rows="5" required></textarea>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">Enviar</button>
          </div>
          <div id="formSuccess" class="form-success">
            Mensagem enviada com sucesso! Entraremos em contato em breve.
          </div>
        </form>
      </div>
      <div class="contact-info">
        <div class="info-container">
          <h3>Informações de Contato</h3>
          <p><i class="icon-location"></i> <?php echo $address; ?></p>
          <p><i class="icon-phone"></i> <?php echo $phoneNumber; ?></p>
          <p>
            <i class="icon-email"></i>
            <?php echo $email; ?>
          </p>
          <h4>Horário de Atendimento</h4>
          <p><?php echo $workingHours; ?></p>
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
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
      </div>
    </div>
  </div>
</section>