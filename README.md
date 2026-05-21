<div align="center">
  <h1>Dell Anno - Nacional 2026</h1>
</div>

O Dell Anno 2026 é o uma atualização do site da Dell Anno desenvolvido em Wordpress
  
---

## Índice

- [Sobre](#sobre)
- [Visualização](#visualizacao)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Arquitetura do Projeto](#arquitetura-do-projeto)
- [Como Executar o Projeto](#como-executar-o-projeto)

---

<h2 id="sobre">Sobre:</h2>

Através do painel de gerenciamento (manager), é possível:

- Gerenciar conteúdos de cada seção da página em pt-BR:
    - SEO
    - Home
    - Institucional
    - Produtos
    - Lojas
    - Inspiração
    - Acabamentos
    - Blog
    - Catálogos
    - Contato
    - Política de privacidade 


E através do site para o público:

- Visualizar as páginas:
    - **Home**: apresenta um pouco sobre cada seção do site
    - **Brand**: história, timeline, origem, tradição, sustentabilidade e características da marca
    - **Produtos**: separado por segmento, cada um deles fala com detalhes sobre àquele produto
    - **Lojas**: dividida por localidade, cada uma com detalhes
    - **Global Living**: site externo
    - **Inspire-se**: separa showrooms, projetos e mostras de decoração para o usuário 
    - **Frame**: posts Dell Anno
    - **Catálogos **: categoria com os podutos
    - **Programa comercial**: site externo

---


<h2 id="visualizacao">Visualização:</h2>

<img width="400" alt="image home" src="https://github.com/user-attachments/assets/a09d56bd-d9fb-4f62-9d3a-d645ef602b63" />
<img width="400" alt="image marca" src="https://github.com/user-attachments/assets/c6f9c390-c5fd-41db-aad8-984cd8ac8527" />
<img width="400" alt="image produtos" src="https://github.com/user-attachments/assets/e5433dda-09d7-4391-a99c-2f0a6340d6a0" />
<img width="400" alt="image lojas" src="https://github.com/user-attachments/assets/fa3989df-0607-498b-98c7-1bec7615e814" />


---

<h2 id="tecnologias-utilizadas">Tecnologias Utilizadas:</h2>

### Back-end:
- **Laravel (^12.0)**: framework PHP para construção do projeto, gerenciamento de rotas, autenticação e etc.
- **PHP (^8.2)**: linguagem de desenvolvimento
- **Laravel Sanctum (^4.0)**: autenticação e proteção de rotas
- **Inertia.js (^2.0)**: integração entre backend Laravel e frontend React sem necessidade de API tradicional
- **Laravel Localization (^2.2)**: gerenciamennto de idiomas e rotas traduzidas
- **Ziggy (^2.0)**: compartilhamento de rotas Laravel diretamente no frontend React
- **Laravel Tinker (^2.9)**: ferramenta para testes e execução de comandos no ambiente
- **Laravel PT-BR Validator (*)**: validações adaptadas para formato brasileiro
- **Tinify (^1.6)**: ferramenta para comprimir imagens

### Front-end:
- **React (^18.2.0)**: biblioteca para construção de interfaces
- **Inertia React (^2.0.0)**: integração entre Laravel e React sem necessidade de API REST tradicional
- **Vite (^6.2.4)**: ferramenta de build e desenvolvimento rápido
- **Laravel Vite Plugin (^1.2.0)**: integração entre Laravel e Vite
- **Tailwind CSS (^3.2.1)**: framework para estilização
- **Tailwind Forms (^0.5.3)**: plugin para estilização de formulários no Tailwind
- **PostCSS (^8.4.31)**: processador de CSS usado junto do Tailwind
- **Autoprefixer (^10.4.12)**: adiciona prefixos CSS automaticamente para compatibilidade entre navegadores

### UI e experiência do usuário:
- **Font Awesome React (^0.2.2)**: biblioteca de ícones para interface
- **Font Awesome Free Solid Icons (^6.7.2)**: conjunto de ícones sólidos
- **Headless UI (^2.0.0)**: componentes acessíveis e sem estilos pré-definidos
- **Swiper (^11.2.6)**: criação de sliders e carrosséis
- **GSAP (^3.12.7)**: biblioteca para animações
- **Lenis (^1.0.42)**: implementação de scroll suave
- **React Select (^5.10.1)**: select customizado
- **React Tag Input (^6.10.6)**: gerenciamento e criação de tags
- **Yet Another React Lightbox (^3.25.0)**: exibição de imagens em lightbox

### Tabelas, dados e formulários:
- **React Input Mask (^2.0.4)**: máscaras para inputs como CPF e telefones
- **React SortableJS (^6.1.4)**: drag and drop para ordenação de elementos
- **Tailwind Forms (^0.5.3)**: melhoria visual para campos de formulário

### Upload e manipulação de arquivos:
- **React Dropzone (^14.3.8)**: upload de arquivos via drag and drop
- **React Image Crop (^11.0.7)**: recorte de imagens no navegador
- **browser-image-compression (^2.0.2)**: compressão de imagens

### Editor de texto:
- **Tiptap (^2.11.7)**: editor de texto altamente customizável
- Extensões utilizadas:
    - **Starter Kit**: funcionalidades básicas do editor
    - **Text**: manipulação de texto
    - **Image**: suporte para imagens
    - **Link**: gerenciamento de links
    - **Underline**: sublinhado no texto
    - **Text Align**: alinhamento de texto
    - **Table**: criação de tabelas
    - **Table Row**: gerenciamento de linhas
    - **Table Header**: cabeçalhos de tabelas
    - **Table Cell**: células de tabelas
    - **List Item**: manipulação de listas
    - **Figure Extension (@pentestpad/tiptap-extension-figure)**: suporte a figuras e legendas

---

<h2 id="arquitetura-do-projeto">Arquitetura principal do Projeto:</h2>

```bash
Dell-Anno-Naciona-2026
│
├── app
│   ├── Http
│   │   ├── Controllers    # Controladores responsáveis pelas requisições e retornar respostas (separado por Manager)
│   │   ├── Middleware     # Interceptação, autenticação e tratamento de requisições
│   │   ├── Requests       # Validação e autorização de formulários e requisições (separado por Manager)
│   │   ├── helpers.php    # Auxiliares globais utilizados no projeto
│   ├── Models             # Representação das tabelas do banco (Eloquent)
│   ├── Providers          # Configuração de pacotes
│   ├── Services           #Regras de negócio
├── bootstrap              # Inicialização do framework
├── config                 # Arquivos de configuração
├── database               # Migrations, seeds e factories
├── public                 # Diretório público acessível pelo navegador
│   ├── admin              # Arquivos relacionados ao Manager
│   ├── content            # Arquivos relacionados as páginas e gerenciáveis pelo Manager
│   ├── files              # Arquivos do site institucional como pdfs e etc.
│   ├── site               # Arquivos do site institucional
├── resources              # Frontend e recursos
│   ├── css                # Estilização 
│   ├── js                 # Componentes, páginas, hooks e layouts (separados por Manager)
│   ├── views              # Templates e views do Laravel/Inertia
├── routes                 # Definição das rotas web e Manager
├── storage                # Arquivos gerados (logs, cache e etc.)
├── tests
│

```

---

<h2 id="como-executar-o-projeto">Como Executar o Projeto:</h2>

1. Clone o repositório:

```bash
git clone https://github.com/Octal-web/Dell-Anno-Nacional-2026.git
cd Dell-Anno-Nacional-2026
```

2. Instale as dependências do Front-end:

```bash
npm install
```

3. Instale as dependências do Back-end:

```bash
composer install
```

4. Configure o ambiente

Crie o arquivo .env:

```bash
cp .env.example .env
```

Gere a chave da aplicação:
```bash
php artisan key:generate
```

5. Rode o projeto:
```bash
npm run dev
php artisan serve
```


