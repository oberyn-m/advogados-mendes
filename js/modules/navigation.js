// Script simples para navegação na página única
document.addEventListener('DOMContentLoaded', function() {
    // Selecionar elementos do menu
    const menuLinks = document.querySelectorAll('.menu-link');
    const sections = document.querySelectorAll('section');
    
    // Altura do header para o scroll considerar o espaço fixo no topo
    const headerHeight = document.querySelector('.header').offsetHeight;
    
    // Adicionar evento de clique a cada link do menu
    menuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Obter o destino a partir do atributo href
            const targetId = this.getAttribute('href');
            
            // Caso especial para o link de início
            if (targetId === '#inicio') {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                return;
            }
            
            // Para outras seções, rolar até elas
            const targetSection = document.querySelector(targetId);
            
            if (targetSection) {
                const targetPosition = targetSection.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
});
