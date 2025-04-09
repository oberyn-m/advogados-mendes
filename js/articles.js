/**
 * Gerenciamento de Artigos
 * Este arquivo unifica todas as funcionalidades relacionadas aos artigos do site
 */

// Variáveis globais para controle de paginação
let currentPage = 1;
const articlesPerPage = 3;
let sortedArticles = [];
let isLoading = false;

// Carrega os artigos quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
  // Carregar artigos
  displayArticles();
  
  // Adiciona observer para carregar imagens apenas quando visíveis
  setupLazyLoading();
});

/**
 * Configura o lazy loading para imagens de artigos
 */
function setupLazyLoading() {
  if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src;
          img.classList.remove('lazy');
          observer.unobserve(img);
        }
      });
    });
    
    // Observa todas as imagens com classe lazy
    document.querySelectorAll('img.lazy').forEach(img => {
      imageObserver.observe(img);
    });
  } else {
    // Fallback para navegadores que não suportam IntersectionObserver
    document.querySelectorAll('img.lazy').forEach(img => {
      img.src = img.dataset.src;
      img.classList.remove('lazy');
    });
  }
}

/**
 * Exibe os artigos na página
 * Usa os dados do arquivo articles-data.js em vez de carregar o arquivo markdown
 */
function displayArticles() {
  try {
    // Verifica se os dados de artigos estão disponíveis (de articles-data.js)
    if (typeof articlesData === 'undefined') {
      throw new Error("Dados de artigos não encontrados. Verifique se o arquivo articles-data.js está incluído corretamente.");
    }
    
    // Ordenar artigos por data (mais recentes primeiro)
    sortedArticles = [...articlesData].sort((a, b) => {
      // Converter data no formato DD-MM-YYYY para objetos Date
      const dateA = convertToDate(a.date);
      const dateB = convertToDate(b.date);
      
      // Ordenar decrescente (mais recente primeiro)
      return dateB - dateA;
    });
    
    // Exibir artigos da página atual
    renderArticlesPage(currentPage);
    
  } catch (error) {
    const articlesContainer = document.getElementById("articlesContainer");
    if (articlesContainer) {
      articlesContainer.innerHTML = `
        <div class="no-articles-message">
          <p>Erro ao carregar artigos: ${error.message}</p>
        </div>
      `;
    }
  }
}

/**
 * Converte string de data no formato DD-MM-YYYY para objeto Date
 */
function convertToDate(dateString) {
  const [day, month, year] = dateString.split('-').map(Number);
  return new Date(year, month - 1, day);
}

/**
 * Renderiza os artigos da página especificada
 */
function renderArticlesPage(page) {
  if (isLoading) return;
  isLoading = true;
  
  const articlesContainer = document.getElementById("articlesContainer");
  const modalsContainer = document.getElementById("articleModalsContainer");
  
  if (!articlesContainer) {
    isLoading = false;
    return;
  }
  
  // Calcular índices de início e fim para a página atual
  const startIndex = (page - 1) * articlesPerPage;
  const endIndex = Math.min(startIndex + articlesPerPage, sortedArticles.length);
  
  // Artigos para a página atual
  const pageArticles = sortedArticles.slice(startIndex, endIndex);
  
  // Limpar containers
  articlesContainer.innerHTML = "";
  if (modalsContainer) modalsContainer.innerHTML = "";
  
  // Verificar se há artigos para exibir
  if (pageArticles.length === 0) {
    articlesContainer.innerHTML = `
      <div class="no-articles-message">
        <p>Não há artigos disponíveis para exibir.</p>
      </div>
    `;
    isLoading = false;
    return;
  }
  
  // Renderizar artigos da página atual
  pageArticles.forEach((article, index) => {
    const articleId = `article-${startIndex + index}`;
    
    // Create article card
    const articleCard = document.createElement("div");
    articleCard.className = "article-card";
    articleCard.setAttribute("data-article", articleId);
    
    articleCard.innerHTML = `
      <div class="article-image">
        <img class="lazy" data-src="${article.image}" alt="${article.title}" loading="lazy" onerror="this.onerror=null; this.src='img/placeholder.jpg';">
      </div>
      <div class="article-content">
        <h3>${article.title}</h3>
        <p class="article-date">${article.date}</p>
        <div class="article-description">
          <p>${article.description}</p>
        </div>
      </div>
    `;
    
    articlesContainer.appendChild(articleCard);
    
    // Create article modal
    if (modalsContainer) {
      const modal = document.createElement("div");
      modal.id = articleId;
      modal.className = "article-modal";
      
      modal.innerHTML = `
        <div class="article-modal-content">
          <span class="article-modal-close">&times;</span>
          <div class="modal-content-wrapper">
            <div class="article-modal-image">
              <img class="lazy" data-src="${article.image}" alt="${article.title}" loading="lazy" onerror="this.onerror=null; this.src='img/placeholder.jpg';">
            </div>
            <div class="article-modal-text">
              <h2>${article.title}</h2>
              <p class="article-date">${article.date}</p>
              <div class="article-modal-body">
                ${article.content}
              </div>
            </div>
          </div>
        </div>
      `;
      
      modalsContainer.appendChild(modal);
    }
  });
  
  // Adicionar controles de paginação
  addPaginationControls(articlesContainer, page, Math.ceil(sortedArticles.length / articlesPerPage));
  
  // Adiciona event listeners aos modais criados
  initializeArticleModals();
  
  // Configura lazy loading para as novas imagens
  setupLazyLoading();
  
  isLoading = false;
}

/**
 * Adiciona controles de paginação para a seção de artigos
 */
function addPaginationControls(container, currentPage, totalPages) {
  // Encontrar a seção de artigos completa para adicionar a paginação
  const articlesSection = document.getElementById('artigos');
  
  // Remover paginação anterior se existir
  const oldPagination = articlesSection.querySelector('.gallery-pagination');
  if (oldPagination) {
    oldPagination.remove();
  }
  
  // Criar container para paginação para abranger toda a área dos artigos
  const paginationDiv = document.createElement('div');
  paginationDiv.className = 'pagination gallery-pagination';
  
  // Botão anterior com ícone de seta
  const prevButton = document.createElement('button');
  prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
  prevButton.className = currentPage > 1 ? 'pagination-arrow prev-arrow' : 'pagination-arrow prev-arrow disabled';
  prevButton.setAttribute('aria-label', 'Página anterior');
  prevButton.disabled = currentPage <= 1;
  
  if (currentPage > 1) {
    prevButton.addEventListener('click', () => {
      if (isLoading) return;
      currentPage--;
      renderArticlesPage(currentPage);
      // Scroll suave para o topo da seção de artigos
      document.getElementById('artigos').scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  }
  
  paginationDiv.appendChild(prevButton);
  
  // Botão próximo com ícone de seta
  const nextButton = document.createElement('button');
  nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
  nextButton.className = currentPage < totalPages ? 'pagination-arrow next-arrow' : 'pagination-arrow next-arrow disabled';
  nextButton.setAttribute('aria-label', 'Próxima página');
  nextButton.disabled = currentPage >= totalPages;
  
  if (currentPage < totalPages) {
    nextButton.addEventListener('click', () => {
      if (isLoading) return;
      currentPage++;
      renderArticlesPage(currentPage);
      // Scroll suave para o topo da seção de artigos
      document.getElementById('artigos').scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  }
  
  paginationDiv.appendChild(nextButton);
  
  // Adicionar paginação à seção de artigos, não ao container
  articlesSection.appendChild(paginationDiv);
  
  // Indicador de página separado (abaixo da grid)
  const pageIndicatorDiv = document.createElement('div');
  pageIndicatorDiv.className = 'pagination-indicator-container';
  pageIndicatorDiv.style.textAlign = 'center';
  pageIndicatorDiv.style.marginTop = '20px';
  
  const pageIndicator = document.createElement('span');
  pageIndicator.innerHTML = `<span class="current-page">${currentPage}</span><span class="page-separator">/</span><span class="total-pages">${totalPages}</span>`;
  pageIndicator.className = 'pagination-indicator';
  
  pageIndicatorDiv.appendChild(pageIndicator);
  container.appendChild(pageIndicatorDiv);
}

/**
 * Inicializa os eventos dos modais de artigos
 */
function initializeArticleModals() {
  // Seleciona todos os cards de artigos
  const articleCards = document.querySelectorAll('.article-card');
  
  // Adiciona event listener para cada card
  articleCards.forEach(card => {
    card.addEventListener('click', function() {
      // Obtém o ID do artigo
      const articleId = this.getAttribute('data-article');
      
      // Abre o modal correspondente
      if (articleId) {
        const modal = document.getElementById(articleId);
        if (modal) {
          modal.style.display = 'block';
          // Impede o scroll da página
          document.body.style.overflow = 'hidden';
        }
      }
    });
  });
  
  // Adiciona event listener para os botões de fechar
  const closeButtons = document.querySelectorAll('.article-modal-close');
  closeButtons.forEach(button => {
    button.addEventListener('click', function() {
      const modal = this.closest('.article-modal');
      if (modal) {
        modal.style.display = 'none';
        // Restaura o scroll da página
        document.body.style.overflow = '';
      }
    });
  });
  
  // Fecha o modal ao clicar fora do conteúdo
  const modals = document.querySelectorAll('.article-modal');
  modals.forEach(modal => {
    modal.addEventListener('click', function(e) {
      if (e.target === this) {
        this.style.display = 'none';
        // Restaura o scroll da página
        document.body.style.overflow = '';
      }
    });
  });
  
  // Fecha o modal ao pressionar ESC
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      const openModal = document.querySelector('.article-modal[style*="display: block"]');
      if (openModal) {
        openModal.style.display = 'none';
        // Restaura o scroll da página
        document.body.style.overflow = '';
      }
    }
  });
}

/**
 * Funções auxiliares para extração de dados do markdown
 */
function extractTitle(markdown) {
  const match = markdown.match(/^## (.*?)$/m);
  return match ? match[1].trim() : "Artigo";
}

function extractDate(markdown) {
  const match = markdown.match(/^Data: (.*?)$/m);
  return match ? match[1].trim() : "";
}

function extractImageUrl(markdown) {
  const match = markdown.match(/^!\[.*?\]\((.*?)\)/m);
  return match ? match[1].trim() : "img/blog-placeholder.jpg";
}

function extractDescription(markdown, title, date, imageUrl) {
  const lines = markdown.split("\n");
  let description = "";
  for (const line of lines) {
    const trimmedLine = line.trim();
    if (
      trimmedLine !== "" &&
      !trimmedLine.startsWith("## ") &&
      !trimmedLine.startsWith("Data: ") &&
      !trimmedLine.startsWith("![") &&
      trimmedLine !== title &&
      trimmedLine !== date &&
      trimmedLine !== `![${title}](${imageUrl})`
    ) {
      description = trimmedLine;
      break;
    }
  }
  return description;
}

function extractContent(markdown) {
  // A estrutura atual do markdown não tem "Neste artigo"
  // O conteúdo é todo o texto após a descrição
  const lines = markdown.split("\n");
  const title = extractTitle(markdown);
  const date = extractDate(markdown);
  const imageUrl = extractImageUrl(markdown);
  const description = extractDescription(markdown, title, date, imageUrl);
  
  // Encontra o índice da descrição
  const descriptionIndex = lines.findIndex(line => line.trim() === description);
  
  if (descriptionIndex !== -1 && descriptionIndex + 1 < lines.length) {
    // O conteúdo começa na linha após a descrição
    const contentLines = lines.slice(descriptionIndex + 1).filter(line => line.trim() !== "" && !line.includes("---"));
    return contentLines.map(line => `<p>${line}</p>`).join("");
  }
  
  return "";
}