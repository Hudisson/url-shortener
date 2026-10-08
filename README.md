# URL Shortener

Um encurtador de URLs desenvolvido em **PHP com Laravel 12**, criado com foco em estudo, aplicação de conceitos de arquitetura de software e desenvolvimento de um MVP funcional.

O projeto permite transformar URLs longas em URLs curtas, acompanhar métricas e redirecionar o usuário para a URL original. Cada URL vence um ano após sua criação e URLs vencidas são removidas automaticamente pelo Scheduler.

## Status

**MVP funcional em evolução**

Além da criação e do redirecionamento de URLs curtas, o sistema possui autenticação,
dashboard com métricas e validade automática das URLs.

---

## Funcionalidades

* Criar URLs curtas a partir de uma URL original.
* Gerar códigos curtos utilizando caracteres Base62.
* Garantir que o código gerado seja único.
* Validar a URL informada.
* Redirecionar a URL curta para a URL original.
* Contabilizar os acessos às URLs.
* Permitir desativação de URLs.
* Definir vencimento de um ano a partir da criação de cada URL.
* Exibir datas de criação, atualização e vencimento nas métricas da URL.
* Excluir automaticamente URLs vencidas ao iniciar o Scheduler e, depois, diariamente.
* Interface web para criação de URLs.
* Registrar no log as execuções e falhas da limpeza de URLs vencidas.
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
* MySQL 8.0 ou superior

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

## Configuração do banco de dados

O projeto utiliza MySQL 8. Crie um banco de dados e configure as credenciais no
arquivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=url_shortener
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

Execute as migrations:

```bash
php artisan migrate
```

---

## Executando o projeto

Inicie cada processo em um terminal separado, na pasta do projeto.

**Terminal 1 — servidor Laravel:**

```bash
php artisan serve
```

**Terminal 2 — Vite:**

```bash
npm run dev
```

**Terminal 3 — limpeza inicial e Scheduler:**

```bash
php artisan short-urls:scheduler
```

O comando do Scheduler executa a limpeza imediatamente quando iniciado e mantém
o processo ativo, verificando as tarefas agendadas. A rotina diária remove
somente URLs cujo `expires_at` seja igual ou anterior ao horário da execução.
Mantenha esse processo em execução; em produção, use um gerenciador de processos
como Supervisor ou systemd.

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
* integração dos Controllers;
* cálculo das datas de vencimento;
* remoção de URLs vencidas e preservação das URLs ainda válidas;
* registro de execução e falhas do processo de limpeza.

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
├── ExpiredShortUrlCleanupService.php
└── UniqueShortCodeGenerator.php
```

### Comandos Artisan

```text
app/Console/Commands/
├── CleanupExpiredShortUrlsCommand.php
└── StartShortUrlSchedulerCommand.php
```

`short-urls:cleanup-expired` executa a limpeza uma vez. `short-urls:scheduler`
faz essa execução ao iniciar e, em seguida, inicia o worker contínuo do Scheduler.

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
MySQL 8
```

Cada URL recebe `expires_at` um ano após `created_at`, usando a mesma data e hora
da criação como referência.

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
├── Console/
│   └── Commands/
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

## Estrutura da tabela `short_urls`

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
| `created_at`   | Data de criação                   |
| `updated_at`   | Data da última atualização        |
| `expires_at`   | Vencimento, um ano após a criação |

---

## Escopo atual do MVP

O MVP inclui criação e redirecionamento de URLs, autenticação, gerenciamento pelo
dashboard, métricas básicas, vencimento anual e remoção automática de URLs vencidas.
Novas funcionalidades poderão ser adicionadas conforme a evolução do projeto.

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
