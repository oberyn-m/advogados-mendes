const UI = {
  init: function() {
    this.setupFAQ();
    this.setupScrollToTop();
  },
  
  setupFAQ: function() {
    const faqToggles = document.querySelectorAll('.faq-toggle');
    
    faqToggles.forEach(toggle => {
      toggle.addEventListener('click', function() {
        // Get the parent faq-item
        const faqItem = this.closest('.faq-item');
        
        // Toggle active class on the parent
        faqItem.classList.toggle('active');
        
        // Change the toggle button text to X when active, + when inactive
        this.textContent = faqItem.classList.contains('active') ? '×' : '+';
        
        // Toggle the answer visibility
        const answer = faqItem.querySelector('.faq-answer');
        if (answer) {
          if (faqItem.classList.contains('active')) {
            answer.style.maxHeight = answer.scrollHeight + 'px';
          } else {
            answer.style.maxHeight = '0';
          }
        }
      });
    });
  },
  
  setupScrollToTop: function() {
    // Existing scroll to top functionality
  }
};