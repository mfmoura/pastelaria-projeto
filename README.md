# Pastelaria -- Projeto Fullstack (Laravel + Vue + Docker + PWA)

Bem-vindo ao projeto **Pastelaria**, um sistema completo desenvolvido
como desafio técnico, utilizando:

-   **Laravel 12** (API REST + autenticação + testes)
-   **Vue 3 + Vite** (frontend + PWA)
-   **Docker** (ambiente consistente)
-   **MySQL**
-   **PWA funcional com Service Worker + Manifest**
-   **Arquitetura limpa com Services e Resources**

## ✅ Funcionalidades

-   CRUDL completo de:
    -   **Clientes**
    -   **Produtos**
    -   **Pedidos**
-   API 100% JSON com middleware dedicado
-   Autenticação básica via HTTP
-   Testes com PHPUnit
-   PWA pronto para instalar como app
-   Ambiente completamente dockerizado

## 🐳 Rodando o Projeto com Docker (3 passos)

### 1. Clone o repositório

``` bash
git clone git@github.com:mfmoura/pastelaria-projeto.git
cd pastelaria-projeto
```

### 2. Suba o ambiente

``` bash
docker compose up -d --build
```

### 3. Instale dependências dentro do container

``` bash
docker compose projeto-pastelaria-php-1 exec app composer install
docker compose projeto-pastelaria-php-1 exec app php artisan migrate
docker compose projeto-pastelaria-php-1 exec app php artisan db:seed
docker compose projeto-pastelaria-node-1 exec npm install
docker compose projeto-pastelaria-node-1 exec npm run build
```

Backend: http://localhost\
Frontend (Vite): http://localhost:5173

## 📱 PWA -- Instalação e Funcionamento

-   `manifest.webmanifest`
-   Service Worker configurado
-   Ícones automáticos da ferramenta "Favicon Generator"

Para testar: 1. Abrir DevTools → Application 2. Ver Manifest carregado
3. Ver Service Worker ativo 4. Botão "Instalar aplicativo" aparecerá
conforme navegador

## 🗂 Estrutura de Pastas

    app/
      Http/
        Controllers/
        Middleware/
        Resources/
      Models/
      Services/
    public/
      icons/
    resources/
      js/
    routes/
      api.php

## 🔐 Autenticação Básica

``` bash
curl -u usuario:senha http://localhost/api/clientes
```

## 🌐 Endpoints (CRUDL)

Padronizados para Clientes, Produtos e Pedidos:

    GET    /api/clientes
    GET    /api/clientes/{id}
    POST   /api/clientes
    PUT    /api/clientes/{id}
    DELETE /api/clientes/{id}

## ⚙️ Middleware de JSON Global

Arquivo:

    app/Http/Middleware/ForcarRespostaJson.php

Registrado em:

    bootstrap/app.php

## 🧪 Testes com PHPUnit

``` bash
docker compose exec app php artisan test
```

## 🖼 Ícones e Favicon

Arquivos ficam em:

    public/icons/

Inclui: - `apple-touch-icon.png` - `favicon.ico` - `favicon.svg` -
`favicon-96x96.png` - `web-app-manifest-192x192.png` -
`web-app-manifest-512x512.png`

## 📦 Build do Frontend

``` bash
npm run build
```

## ✅ Tecnologias Utilizadas

-   Laravel 12
-   PHP 8.2+
-   Vue 3 + Vite
-   TailwindCSS
-   Docker
-   MySQL
-   PHPUnit
-   PWA

## ✨ Sobre o Autor

Projeto desenvolvido como parte de um processo seletivo.
