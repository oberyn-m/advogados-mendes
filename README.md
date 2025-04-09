# Mendes Advocacia - Website

![Mendes Advocacia](img/hero-bg.jpg)

## 📋 Sobre o Projeto

Website profissional desenvolvido para o escritório Mendes Advocacia, especializado em Direito Trabalhista. O site foi construído com foco em performance, acessibilidade e experiência do usuário, utilizando tecnologias modernas e boas práticas de desenvolvimento.

### 🌟 Características Principais

- Design moderno e responsivo
- Interface intuitiva e amigável
- Otimizado para SEO
- Alta performance e carregamento rápido
- Totalmente acessível
- Integração com redes sociais e WhatsApp

## 🚀 Tecnologias Utilizadas

- HTML5
- CSS3 (com organização modular)
- JavaScript (Vanilla)
- PHP

### 📚 Bibliotecas e Frameworks

- Font Awesome (ícones)
- Google Fonts
- PHPMailer (para envio de e-mails)

## 🎯 Funcionalidades

- **Header Responsivo**
  - Menu hamburguer para dispositivos móveis
  - Navegação suave entre seções

- **Seções Principais**
  - Hero Section com chamada principal
  - Sobre o escritório
  - Áreas de atuação
  - Artigos jurídicos (gerenciados via JavaScript)
  - FAQ
  - Formulário de contato

- **Recursos Especiais**
  - Botão flutuante de WhatsApp para contato rápido
  - Botão "Voltar ao topo"
  - Sistema de artigos com dados gerenciados via JavaScript
  - Formulário de contato com validação
  - Integração com redes sociais

## 💻 Estrutura do Projeto

```
advogados-mendes/
├── css/               # Arquivos de estilo
│   ├── base/          # Estilos base
│   ├── components/    # Componentes reutilizáveis
│   ├── sections/      # Estilos específicos de seções
│   └── utils/         # Utilitários CSS
├── img/               # Imagens e recursos
├── js/               # Scripts JavaScript
│   ├── modules/      # Módulos JavaScript
│   └── articles-data.js  # Dados dos artigos
├── includes/         # Componentes PHP reutilizáveis
└── vendor/           # Dependências
```

## 🔧 Configuração e Instalação

1. Clone o repositório
2. Configure o servidor web (Apache/Nginx) apontando para o diretório do projeto
3. Configure as variáveis de ambiente no arquivo `.env`:
   ```env
   SMTP_HOST=seu_smtp
   SMTP_USER=seu_email
   SMTP_PASS=sua_senha
   SMTP_PORT=porta_smtp
   ```
4. Ajuste as configurações no arquivo `config.php`

## 📱 Responsividade

O site é totalmente responsivo e se adapta aos seguintes breakpoints:
- Mobile: < 768px
- Tablet: 768px - 1024px
- Desktop: > 1024px

## 🔒 Segurança

- Validação de formulários
- Sanitização de dados
- Proteção contra CSRF
- Configurações seguras de envio de e-mail

## 📄 Licença

Este projeto está sob a licença MIT.

## 📞 Contato

Mendes Advocacia
- Website: [www.mendesadvocacia.com.br](http://www.mendesadvocacia.com.br)
- Email: contato@mendesadvocacia.com.br
- Telefone: (11) 1234-5678

---
Desenvolvido com ❤️ por [Isaac Matos](https://github.com/oberyn-m)