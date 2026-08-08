# URL Shortener

Um encurtador de URLs desenvolvido em **PHP com Laravel 12**, criado com foco em estudo, aplicação de conceitos de arquitetura de software e desenvolvimento de um MVP funcional.

O projeto permite transformar URLs longas em URLs curtas e redirecionar o usuário para a URL original.

## Status

**MVP funcional**

O projeto atualmente possui o fluxo principal de criação e redirecionamento de URLs curtas.

Funcionalidades adicionais poderão ser implementadas posteriormente, após a validação do MVP.

---

## Funcionalidades

* Criar URLs curtas a partir de uma URL original.
* Gerar códigos curtos utilizando caracteres Base62.
* Garantir que o código gerado seja único.
* Validar a URL informada.
* Redirecionar a URL curta para a URL original.
* Contabilizar os acessos às URLs.
* Permitir desativação de URLs.
* Interface web para criação de URLs.
* Resposta JSON para clientes de API.
* Testes automatizados com PHPUnit.

---

## Tecnologias

* PHP 8+
* Laravel 12
* MySQL
* PHPUnit
* Vite
* HTML
* CSS
* Blade

---

## Requisitos

Antes de executar o projeto, certifique-se de possuir:

* PHP 8.2 ou superior
* Composer
* Node.js
* NPM

Verifique as versões instaladas:

```bash
php -v
composer -V
node -v
npm -v
```

---

## Instalação

Clone o repositório:

```bash
git clone https://github.com/Hudisson/url-shortener.git
```

Entre no diretório:

```bash
cd url-shortener
```

Instale as dependências do PHP:

```bash
composer install
```

Instale as dependências do frontend:

```bash
npm install
```

Crie o arquivo de ambiente:

```bash
cp .env.example .env
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

---

## Banco de dados

O projeto utiliza MySQL.

Crie o arquivo do banco caso ele ainda não exista:

```bash
touch database/database.sqlite
```

No arquivo `.env`, configure:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=database_user
DB_PASSWORD=database_password
```

Execute as migrations:

```bash
php artisan migrate
```

---

## Executando o projeto

Para iniciar o servidor Laravel:

```bash
php artisan serve
```

Em outro terminal, execute o Vite:

```bash
npm run dev
```

Depois acesse:

```text
http://127.0.0.1:8000
```

---

## Utilização

### Interface Web

Na página inicial, informe uma URL válida:

```text
https://example.com
```

Clique em:

**Encurtar URL**

O sistema irá gerar uma URL curta, por exemplo:

```text
http://127.0.0.1:8000/7kK5l1
```

Ao acessar a URL curta, o sistema redirecionará o usuário para a URL original.

---

## API HTTP

O endpoint de criação também pode ser utilizado por clientes HTTP como Insomnia ou Thunder Client.

### Criar URL curta

```http
POST /shorten
```

Parâmetro:

```json
{
    "url": "https://example.com"
}
```

Resposta:

```json
{
    "short_code": "7kK5l1",
    "original_url": "https://example.com"
}
```

A URL curta pode então ser acessada através de:

```text
/{short_code}
```

Por exemplo:

```text
http://127.0.0.1:8000/7kK5l1
```

---

## Testes

O projeto possui testes unitários e testes de integração/feature.

Para executar toda a suíte:

```bash
php artisan test
```

Os testes cobrem, entre outros:

* geração de códigos;
* geração de códigos únicos;
* validação de URLs;
* persistência de URLs;
* criação de URLs curtas;
* tratamento de URLs inexistentes;
* tratamento de URLs inativas;
* incremento do contador de cliques;
* redirecionamento;
* integração dos Controllers.

---

## Arquitetura

O projeto utiliza uma separação de responsabilidades baseada principalmente em:

```text
Controller
    ↓
Service
    ↓
Repository
    ↓
Model
    ↓
Database
```

### Controllers

Responsáveis pela camada HTTP.

```text
app/Http/Controllers/
├── RedirectController.php
└── ShortUrlController.php
```

### Services

Concentram as regras de negócio:

```text
app/Services/
├── ShortUrlRedirectService.php
├── ShortUrlService.php
└── UniqueShortCodeGenerator.php
```

### Repositories

Responsáveis pelo acesso aos dados:

```text
app/Repositories/
├── Contracts/
│   └── ShortUrlRepositoryInterface.php
└── ShortUrlRepository.php
```

### Validation

Responsável pela validação das URLs:

```text
app/Validation/
├── Contracts/
│   └── UrlValidatorInterface.php
└── UrlValidator.php
```

### Generators

Responsáveis pela geração dos códigos:

```text
app/Support/
├── Contracts/
│   ├── ShortCodeGeneratorInterface.php
│   └── UniqueShortCodeGeneratorInterface.php
└── Generators/
    └── ShortCodeGenerator.php
```

---

## Fluxo de criação

```text
Usuário
   ↓
POST /shorten
   ↓
ShortUrlController
   ↓
ShortUrlService
   ↓
UrlValidator
   ↓
UniqueShortCodeGenerator
   ↓
ShortCodeGenerator
   ↓
ShortUrlRepository
   ↓
SQLite
```

---

## Fluxo de redirecionamento

```text
GET /{shortCode}
        ↓
RedirectController
        ↓
ShortUrlRedirectService
        ↓
ShortUrlRepository
        ↓
ShortUrl
        ↓
Incrementa clicks
        ↓
Redirecionamento
        ↓
URL original
```

---

## Estrutura principal

```text
app/
├── Http/
│   └── Controllers/
├── Logging/
├── Models/
├── Providers/
├── Repositories/
├── Services/
├── Support/
└── Validation/

database/
├── factories/
└── migrations/

resources/
├── css/
├── js/
└── views/
    ├── layouts/
    └── short-url/

routes/
├── console.php
└── web.php

tests/
├── Feature/
└── Unit/
```

---

## Banco de dados

A tabela principal do projeto é:

```text
short_urls
```

Com os seguintes campos:

| Campo          | Descrição                  |
| -------------- | -------------------------- |
| `id`           | Identificador da URL       |
| `original_url` | URL original               |
| `short_code`   | Código único da URL curta  |
| `clicks`       | Quantidade de acessos      |
| `is_active`    | Indica se a URL está ativa |
| `created_at`   | Data de criação            |
| `updated_at`   | Data da última atualização |

---

## Escopo atual do MVP

O objetivo desta primeira versão é validar o funcionamento básico de um serviço de encurtamento de URLs.

Por isso, funcionalidades como:

* autenticação de usuários;
* painel administrativo;
* histórico de URLs;
* expiração automática;
* limite de cliques;
* analytics;
* QR Code;
* gerenciamento avançado de URLs;
* cache;

não fazem parte do escopo atual.

Essas funcionalidades poderão ser adicionadas posteriormente.

---

## Objetivos do projeto

Além de construir um serviço funcional, o projeto também tem como objetivo aplicar conceitos de:

* Programação Orientada a Objetos;
* princípios de responsabilidade única;
* interfaces;
* injeção de dependências;
* Repository Pattern;
* Service Layer;
* testes automatizados;
* validação;
* separação de responsabilidades;
* desenvolvimento incremental.

---

## Licença

Este projeto foi desenvolvido para fins de estudo, prática e construção de portfólio.
