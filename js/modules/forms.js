const Forms = {
  init: function() {
    this.setupContactForm();
    
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
      telefoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        
        if (value.length > 2) {
          value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
        }
        if (value.length > 9) {
          value = `${value.slice(0, 9)}-${value.slice(9)}`;
        }
        
        e.target.value = value;
      });
    }
  },

  setupContactForm: function() {
    const contactForm = document.getElementById('contactForm');
    const formSuccess = document.getElementById('formSuccess');
    
    if (contactForm) {
      contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(contactForm);
        const submitButton = contactForm.querySelector('button[type="submit"]');
        
        if (submitButton) {
          submitButton.disabled = true;
          submitButton.textContent = 'Enviando...';
        }
        
        try {
          const response = await fetch('process_contact.php', {
            method: 'POST',
            body: formData
          });

          const data = await response.json();
          
          if (data.success === true) {
            contactForm.reset();
            
            const telefoneInput = document.getElementById('telefone');
            if(telefoneInput && telefoneInput.trigger) {
                telefoneInput.trigger('input');
            }
            
            formSuccess.style.display = 'block';
            setTimeout(() => {
              formSuccess.style.display = 'none';
            }, 5000);
          }
        } catch (error) {
          console.error('Erro:', error);
        } finally {
          if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = 'Enviar';
          }
          
          contactForm.reset();
        }
      });
    }
  }
};
