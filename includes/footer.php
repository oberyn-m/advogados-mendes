<footer class="footer">
  <div class="container">
    <div class="footer-content">
      <div class="footer-logo">
        <a href="/advogados-mendes/" class="footer-brand">Mendes Advocacia</a>
      </div>
      <div class="footer-contact">
        <h3>Contato</h3>
        <p><i class="fas fa-map-marker-alt"></i><?php echo $siteAddress; ?></p>
        <p><i class="fas fa-phone"></i><?php echo $sitePhone; ?></p>
        <p><i class="fas fa-envelope"></i><?php echo $siteEmail; ?></p>
      </div>
      <div class="footer-social">
        <h3>Redes Sociais</h3>
        <div class="social-icons">
          <a href="<?php echo $linkFacebook; ?>" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="<?php echo $linkInstagram; ?>" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="<?php echo $linkLinkedin; ?>" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; <?php echo date('Y'); ?> <a href="/advogados-mendes/">Mendes Advocacia</a>. Todos os direitos reservados.</p>
    </div>
  </div>
</footer>

<script src="js/modules/navigation.js"></script>
<script src="js/modules/forms.js"></script>
<script src="js/articles-data.js"></script>
<script src="js/articles.js"></script>
<script src="js/main.js"></script>

<div class="floating-icons">
  <div class="floating-icons-container">
    <a href="https://wa.me/<?php echo $numberWhatsapp; ?>?text=<?php echo urlencode($messageWhatsapp); ?>" target="_blank" class="floating-icon whatsapp-icon" aria-label="Contato via WhatsApp">
      <i class="fab fa-whatsapp"></i>
    </a>
    <button class="floating-icon scroll-top-icon" aria-label="Voltar ao topo">
      <i class="fas fa-arrow-up"></i>
    </button>
  </div>
</div>

</body>
</html>