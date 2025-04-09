const Forms = {
  init: function() {
    this.setupContactForm();
    this.setupPhoneMask();
    this.setupEmailValidation();
    this.setupCharacterCount();
  },

  setupCharacterCount: function() {
    const fields = [
      { input: 'nome', counter: 'nomeCount', max: 200 },
      { input: 'assunto', counter: 'assuntoCount', max: 200 },
      { input: 'mensagem', counter: 'mensagemCount', max: 1000 }
    ];

    fields.forEach(field => {
      const input = document.getElementById(field.input);
      const counter = document.getElementById(field.counter);
      
      if (input && counter) {
        const updateCount = () => {
          const length = input.value.length;
          counter.textContent = `${length}/${field.max}`;
          
          counter.classList.remove('limit-near', 'limit-reached');
          if (length >= field.max) {
            counter.classList.add('limit-reached');
          } else if (length >= field.max * 0.9) {
            counter.classList.add('limit-near');
          }
        };

        input.addEventListener('input', updateCount);
        updateCount();
      }
    });
  },

  setupPhoneMask: function() {
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
      telefoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value.length > 11) value = value.slice(0, 11);
        
        if (value.length > 2) {
          value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
        }
        
        if (value.length > 0) {
          const numeroSemDDD = value.replace(/\D/g, '').slice(2);
          if (numeroSemDDD.length > 4) {
            if (numeroSemDDD.length === 9) {
              value = value.slice(0, 10) + '-' + value.slice(10);
            } else if (numeroSemDDD.length === 8) {
              value = value.slice(0, 9) + '-' + value.slice(9);
            }
          }
        }
        
        e.target.value = value;
      });
    }
  },

  setupEmailValidation: function() {
    const emailInput = document.getElementById('email');
    const telefoneInput = document.getElementById('telefone');
    
    if (emailInput) {
      emailInput.addEventListener('input', function() {
        this.classList.remove('error');
        const errorElement = document.getElementById('emailError');
        if (errorElement) {
          errorElement.textContent = '';
        }
      });
    }

    if (telefoneInput) {
      telefoneInput.addEventListener('input', function() {
        this.classList.remove('error');
        const errorElement = document.getElementById('telefoneError');
        if (errorElement) {
          errorElement.textContent = '';
        }
      });
    }
  },

  clearErrors: function() {
    document.querySelectorAll('.error-message').forEach(message => message.textContent = '');
    document.querySelectorAll('.error').forEach(input => input.classList.remove('error'));
  },

  showError: function(inputId, message) {
    const input = document.getElementById(inputId);
    const errorElement = document.getElementById(inputId + 'Error');
    
    if (input && errorElement) {
      input.classList.add('error');
      errorElement.textContent = message;
    }
  },

  validateForm: function(form) {
    let isValid = true;
    this.clearErrors();

    const emailInput = form.querySelector('#email');
    const phoneInput = form.querySelector('#telefone');

    if (emailInput) {
      const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
      if (!emailRegex.test(emailInput.value)) {
        isValid = false;
        this.showError('email', 'Por favor, insira um e-mail válido.');
      }
    }

    if (phoneInput) {
      const phoneValue = phoneInput.value.replace(/\D/g, '');
      const ddd = phoneValue.slice(0, 2);
      
      if (phoneValue.length < 10 || phoneValue.length > 11 || !/^[1-9]{2}$/.test(ddd)) {
        isValid = false;
        this.showError('telefone', 'Por favor, insira um telefone válido no formato (XX) XXXXX-XXXX ou (XX) XXXX-XXXX');
      }
    }

    return isValid;
  },

  resetForm: function(form) {
    // Limpa todos os campos do formulário
    form.reset();
    
    // Limpa manualmente cada campo para garantir
    document.getElementById('nome').value = '';
    document.getElementById('email').value = '';
    document.getElementById('telefone').value = '';
    document.getElementById('assunto').value = '';
    document.getElementById('mensagem').value = '';
    
    // Atualiza os contadores
    document.getElementById('nomeCount').textContent = '0/200';
    document.getElementById('assuntoCount').textContent = '0/200';
    document.getElementById('mensagemCount').textContent = '0/1000';
    
    // Remove classes de erro
    this.clearErrors();
  },

  showSuccessMessage: function(message) {
    // Cria um elemento de mensagem de sucesso se não existir
    let formSuccess = document.getElementById('formSuccess');
    if (!formSuccess) {
      formSuccess = document.createElement('div');
      formSuccess.id = 'formSuccess';
      formSuccess.className = 'alert alert-success';
      formSuccess.style.display = 'none';
      
      // Insere após o formulário
      const contactForm = document.getElementById('contactForm');
      if (contactForm) {
        contactForm.parentNode.insertBefore(formSuccess, contactForm.nextSibling);
      }
    }
    
    // Exibe a mensagem
    formSuccess.textContent = message;
    formSuccess.style.display = 'block';
    
    // Esconde após 5 segundos
    setTimeout(() => {
      formSuccess.style.display = 'none';
    }, 5000);
  },

  setupContactForm: function() {
    const self = this;
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
      contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!self.validateForm(this)) {
          return;
        }
        
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.textContent = 'Enviando...';
        
        const formData = new FormData(this);
        
        fetch('process_contact.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success === true) {
            // Limpa o formulário
            self.resetForm(contactForm);
            
            // Mostra mensagem de sucesso
            self.showSuccessMessage(data.message);
          } else {
            // Mostra mensagem de erro
            alert(data.message);
          }
        })
        .catch(error => {
          console.error('Erro:', error);
          alert('Ocorreu um erro ao enviar a mensagem. Por favor, tente novamente.');
        })
        .finally(() => {
          submitButton.disabled = false;
          submitButton.textContent = 'Enviar';
        });
      });
    }
  }
};
