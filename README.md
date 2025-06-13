
# Proxxy Products API - Laravel

## 📦 Sobre o Projeto

API RESTful desenvolvida em Laravel com o objetivo de gerenciar produtos de uma loja virtual, contemplando todas as funcionalidades solicitadas no teste técnico para Backend Developer da Proxxy.

Este projeto foi construído com foco em boas práticas, organização de código, segurança, testes e documentação clara.

**Desenvolvido por:** Pedro Oliveira  
**Framework:** Laravel 10.x  
**Banco de dados:** MySQL  
**Autenticação:** Laravel Sanctum

---

## ✅ Funcionalidades

### Requisitos Atendidos

- CRUD completo de produtos
- Campos: nome, descrição, preço, quantidade, categoria e imagem
- Listagem com filtros por categoria, faixa de preço e estoque
- Paginação integrada
- Autenticação com JWT via Laravel Sanctum
- Documentação da API com exemplos
- Testes unitários e de funcionalidade

### Diferenciais Implementados

- Upload de imagens para produtos
- Validação centralizada com Form Requests
- API Resources para padronização de respostas
- Segurança reforçada contra injeções SQL com Eloquent
- Seeders para facilitar testes e homologação

---

## 🧰 Tecnologias e Ferramentas

- **PHP 8.1+** com Laravel 10
- **MySQL** como banco de dados relacional
- **Laravel Sanctum** para autenticação JWT
- **PHPUnit** para testes
- **Eloquent ORM** para interação com banco
- **Form Requests** e **API Resources**

---

## 🗂️ Estrutura de Diretórios

```
proxxy-api-laravel/
├── app/Http/Controllers/
├── app/Http/Requests/
├── app/Http/Resources/
├── app/Models/
├── database/migrations/
├── database/seeders/
├── routes/api.php
├── tests/Feature/
└── storage/app/public/products/
```

---

## ⚙️ Como Rodar o Projeto

### Requisitos

- PHP 8.1+
- Composer
- MySQL
- Laravel CLI
- (Sugestão) Laragon para ambiente local

### Passo a Passo

```bash
# 1. Clonar o repositório
git clone [url-do-repositorio] proxxy-api-laravel
cd proxxy-api-laravel

# 2. Instalar dependências
composer install

# 3. Configurar variáveis de ambiente
cp .env.example .env
php artisan key:generate

# 4. Configurar banco de dados no .env
# DB_DATABASE=proxxy_api etc.

# 5. Criar o banco e rodar migrations + seeders
php artisan migrate --seed

# 6. Criar link simbólico para uploads
php artisan storage:link

# 7. Iniciar o servidor
php artisan serve
```

A aplicação estará disponível em: `http://localhost:8000`

---

## 📌 Endpoints da API

A base da API é:

```
http://localhost:8000/api
```

### Autenticação (Sanctum)

- `POST /auth/register` - Registro de usuário
- `POST /auth/login` - Login
- `POST /auth/logout` - Logout (token revogado)
- `GET /auth/user` - Dados do usuário logado

### Categorias

- `GET /categories` - Lista (com filtros e paginação)
- `POST /categories` - Criar
- `GET /categories/{id}` - Detalhes
- `PUT /categories/{id}` - Atualizar
- `DELETE /categories/{id}` - Excluir

### Produtos

- `GET /products` - Lista (com filtros e paginação)
- `POST /products` - Criar produto
- `GET /products/{id}` - Detalhes
- `PUT /products/{id}` - Atualizar
- `DELETE /products/{id}` - Remover
- `POST /products/{id}/image` - Upload de imagem

---

## 🧪 Testes Automatizados

```bash
# Todos os testes
php artisan test

# Teste específico
php artisan test --filter ProductTest
```

Cobertura:

- Registro, login e logout
- CRUD de categorias e produtos
- Upload de imagens
- Filtros e paginação
- Validações de dados

---

## 🔒 Segurança

- Autenticação protegida via Laravel Sanctum
- Proteção contra SQL Injection com Eloquent
- Uploads validados e restritos (JPEG, PNG, etc.)
- Middleware de autenticação em rotas protegidas
- Senhas criptografadas com Bcrypt

---

## 🧠 Aprendizados e Desafios

Durante o desenvolvimento deste projeto, aprofundei conhecimentos em:

- Arquitetura MVC
- Autenticação segura com Laravel Sanctum
- Gerenciamento de relacionamentos e migrations
- Testes de feature com PHPUnit
- Boas práticas RESTful e versionamento de banco

---

## ✉️ Contato

Desenvolvido por **Pedro Oliveira**  
Email: [seu-email-aqui]  
LinkedIn: [seu-linkedin-aqui]  
GitHub: [seu-github-aqui]
