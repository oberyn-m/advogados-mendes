document.addEventListener("DOMContentLoaded", function () {
  // Inicializa o módulo Forms
  if (typeof Forms !== 'undefined') {
    Forms.init();
  }
  
  initializeScrollButton();

  
  initializeFAQ();
});

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

function initializeFAQ() {
  const faqItems = document.querySelectorAll(".faq-item");
  const faqToggles = document.querySelectorAll(".faq-toggle");

  faqItems.forEach((item) => {
    const answer = item.querySelector(".faq-answer");
    if (answer) {
      answer.style.maxHeight = "0";
    }
  });

  faqToggles.forEach((toggle) => {
    toggle.addEventListener("click", function () {
      
      const faqItem = this.closest(".faq-item");

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

      faqItem.classList.toggle("active");

      this.textContent = faqItem.classList.contains("active") ? "-" : "+";

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
