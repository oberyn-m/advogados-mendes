document.addEventListener("DOMContentLoaded", function () {
  // Inicializa o botão de scroll
  initializeScrollButton();

  // Inicializa o FAQ
  initializeFAQ();
});

// Inicializa o botão de scroll
function initializeScrollButton() {
  const scrollButton = document.querySelector('.scroll-down');
  if (scrollButton) {
    scrollButton.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.querySelector('#sobre');
      if(target) {
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  }
}

// Inicializa o FAQ
function initializeFAQ() {
  const faqItems = document.querySelectorAll(".faq-item");
  const faqToggles = document.querySelectorAll(".faq-toggle");

  // Inicializa todas as respostas como fechadas
  faqItems.forEach((item) => {
    const answer = item.querySelector(".faq-answer");
    if (answer) {
      answer.style.maxHeight = "0";
    }
  });

  faqToggles.forEach((toggle) => {
    toggle.addEventListener("click", function () {
      // Toggle the active class on the parent faq-item
      const faqItem = this.closest(".faq-item");

      // Fecha todos os outros itens
      faqItems.forEach((item) => {
        if (item !== faqItem && item.classList.contains("active")) {
          item.classList.remove("active");
          const itemAnswer = item.querySelector(".faq-answer");
          if (itemAnswer) {
            itemAnswer.style.maxHeight = "0";
          }
          const itemToggle = item.querySelector(".faq-toggle");
          if (itemToggle) {
            itemToggle.textContent = "+";
          }
        }
      });

      // Abre/fecha o item atual
      faqItem.classList.toggle("active");

      // Change the toggle button text
      this.textContent = faqItem.classList.contains("active") ? "-" : "+";

      // Toggle the visibility of the answer
      const answer = faqItem.querySelector(".faq-answer");
      if (answer) {
        if (faqItem.classList.contains("active")) {
          answer.style.maxHeight = answer.scrollHeight + "px";
        } else {
          answer.style.maxHeight = "0";
        }
      }
    });
  });
}
