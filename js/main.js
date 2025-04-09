// Módulo principal usando IIFE para evitar poluição do escopo global
(function() {
  'use strict';

  // Cache de seletores DOM
  const domElements = {
    scrollButton: null,
    faqItems: null,
    faqToggles: null,
    scrollTopButton: null,
    whatsappButton: null
  };

  // Função para debounce - limita a frequência de execução de uma função
  const debounce = (func, wait) => {
    let timeout;
    return function() {
      const context = this;
      const args = arguments;
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(context, args), wait);
    };
  };

  // Inicialização dos elementos DOM
  const cacheDOMElements = () => {
    domElements.scrollButton = document.querySelector('.scroll-down');
    domElements.faqItems = document.querySelectorAll(".faq-item");
    domElements.faqToggles = document.querySelectorAll(".faq-toggle");
    domElements.scrollTopButton = document.querySelector('.scroll-top-icon');
    domElements.whatsappButton = document.querySelector('.whatsapp-icon');
  };

  const initializeScrollButton = () => {
    if (domElements.scrollButton) {
      domElements.scrollButton.addEventListener('click', (e) => {
        e.preventDefault();
        const target = document.querySelector('#sobre');
        target?.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      });
    }
  };

  const initializeFAQ = () => {
    if (!domElements.faqItems || !domElements.faqToggles) return;

    // Inicializa todos os itens FAQ com altura 0
    domElements.faqItems.forEach(item => {
      const answer = item.querySelector(".faq-answer");
      if (answer) answer.style.maxHeight = "0";
    });

    // Adiciona event listeners aos toggles usando delegação de eventos
    const faqContainer = domElements.faqItems[0]?.parentElement;
    if (!faqContainer) return;

    faqContainer.addEventListener('click', (e) => {
      const toggle = e.target.closest('.faq-toggle');
      if (!toggle) return;

      const faqItem = toggle.closest('.faq-item');
      if (!faqItem) return;

      // Fecha outros itens abertos
      domElements.faqItems.forEach(item => {
        if (item !== faqItem && item.classList.contains("active")) {
          item.classList.remove("active");
          const itemAnswer = item.querySelector(".faq-answer");
          const itemToggle = item.querySelector(".faq-toggle");
          if (itemAnswer) itemAnswer.style.maxHeight = "0";
          if (itemToggle) itemToggle.textContent = "+";
        }
      });

      // Toggle do item atual
      faqItem.classList.toggle("active");
      toggle.textContent = faqItem.classList.contains("active") ? "-" : "+";

      const answer = faqItem.querySelector(".faq-answer");
      if (answer) {
        answer.style.maxHeight = faqItem.classList.contains("active") ? 
          `${answer.scrollHeight}px` : "0";
      }
    });
  };

  const initializeFloatingIcons = () => {
    if (!domElements.scrollTopButton) return;

    const handleScroll = debounce(() => {
      const shouldShow = window.scrollY > 300;
      domElements.scrollTopButton.style.opacity = shouldShow ? '1' : '0';
      domElements.scrollTopButton.style.visibility = shouldShow ? 'visible' : 'hidden';
    }, 100);

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();

    domElements.scrollTopButton.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  };

  // Implementação de lazy loading para imagens
  const lazyLoadImages = () => {
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          if (img.dataset.src) {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
            observer.unobserve(img);
          }
        }
      });
    });

    images.forEach(img => {
      if (img.dataset.src) {
        imageObserver.observe(img);
      }
    });
  };

  // Otimização de recursos com preload
  const preloadCriticalResources = () => {
    const resources = [
      './css/main.css',
      './js/main.js'
    ];

    resources.forEach(resource => {
      const link = document.createElement('link');
      link.rel = 'preload';
      link.href = resource;
      link.as = resource.endsWith('.css') ? 'style' : 'script';
      document.head.appendChild(link);
    });
  };

  // Inicialização otimizada
  const init = () => {
    document.addEventListener("DOMContentLoaded", () => {
      cacheDOMElements();
      if (typeof Forms !== 'undefined') Forms.init();
      initializeScrollButton();
      initializeFAQ();
      initializeFloatingIcons();
      lazyLoadImages();
      preloadCriticalResources();
    });
  };

  // Expõe a função init globalmente
  window.App = { init };
})();

// Inicializa a aplicação
App.init();

// Otimização de scroll
let scrollTimeout;
window.addEventListener('scroll', function() {
    if (scrollTimeout) {
        window.cancelAnimationFrame(scrollTimeout);
    }
    scrollTimeout = window.requestAnimationFrame(function() {
        // Código de scroll aqui
        const header = document.querySelector('.header');
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
});
