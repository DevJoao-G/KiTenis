# KiTenis 👟 (Laravel 12 + Bootstrap + Blade)

KiTenis é um projeto de e-commerce desenvolvido em **Laravel 12 (PHP 8.4)** utilizando **Blade + Bootstrap + JavaScript**, com **painel administrativo em Filament** para gestão de produtos, marcas, estoque e pedidos.  
A loja possui navegação por categorias, busca, carrinho, autenticação e fluxo de checkout em evolução.

---

## ✨ Principais features

### Loja (Front)
- Listagem de produtos + página do produto
- Categorias: Masculino, Feminino, Infantil e Ofertas
- Busca por produto/marca
- Carrinho (session) com contador no header (desktop)
- Autenticação (login/cadastro)
- Páginas: **Minha Conta** e **Meus Pedidos**
- UI com Bootstrap (tema escuro no header e identidade visual da KiTenis)

### Admin (Filament)
- CRUD de produtos
- Marcas (com logo)
- Categorias
- Fotos por cor, estoque e variações
- Controle de promoções / carrossel de ofertas
- Relatórios / visão de vendas (dependendo do módulo configurado)

---

## ✅ Requisitos

- **PHP 8.4+**
- **Composer**
- **Node.js + NPM**
- Banco de dados (MySQL recomendado)
- Extensões PHP comuns do Laravel (pdo, mbstring, openssl, tokenizer, xml, ctype, json, etc.)

---

## 🚀 Instalação (passo a passo)

### 1) Clone o projeto
```bash
git clone https://github.com/SEU_USUARIO/kitennis.git
cd kitennis
```

### 2) Instale dependências PHP
```bash
composer install
```

### 3) Configure o `.env`
Copie o `.env.example` e gere a chave:
```bash
cp .env.example .env
php artisan key:generate
```

Configure o banco no arquivo `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kitennis
DB_USERNAME=root
DB_PASSWORD=
```

### 4) Rode migrations + seed (se existir)
```bash
php artisan migrate --seed
```

### 5) Instale e compile assets (Vite)
```bash
npm install
npm run dev
```

### 6) Suba o servidor
Em outro terminal:
```bash
php artisan serve
```

Acesse:
- Loja: `http://127.0.0.1:8000`
- Admin (Filament): `http://127.0.0.1:8000/admin` *(pode variar conforme configuração)*

---

## 👤 Criar usuário admin (opções)

### Opção A — Seeder (se já existir)
Se o projeto tiver seeders criando admin, rode:
```bash
php artisan db:seed
```

### Opção B — Tinker (manual)
```bash
php artisan tinker
```

Depois:
```php
$user = \App\Models\User::create([
  'name' => 'Admin',
  'email' => 'admin@kitennis.com',
  'password' => bcrypt('password'),
  'is_admin' => true,
]);
```

---

## 🛒 Carrinho / Contador no header

O carrinho é armazenado em **session**.  
O contador do header soma a quantidade dos itens e está configurado para aparecer **somente no desktop (lg+)** via Bootstrap.

Se você alterar a estrutura do carrinho na session, ajuste o cálculo no:
`resources/views/components/navbar.blade.php`

---

## 💳 Pagamentos (Mercado Pago)

A integração com Mercado Pago pode estar em andamento / configurável dependendo da versão do projeto.  
Quando habilitada, você precisará configurar as credenciais no `.env`.

> Dica: mantenha **tokens e chaves fora do front** e nunca suba o `.env` para o GitHub.

---

## 📁 Estrutura do projeto (resumo)

- `app/` → regras de negócio (controllers, models, services)
- `resources/views/` → Blade (front e componentes)
- `resources/css/` e `resources/js/` → assets (Vite)
- `routes/web.php` → rotas da aplicação
- `database/` → migrations / seeders
- `app/Filament/` → recursos do painel admin

---

## 🧪 Rodar testes (se existirem)
```bash
php artisan test
```

---

## 🛡️ Segurança
- **Nunca faça commit do `.env`**
- Use `APP_DEBUG=false` em produção
- Utilize variáveis de ambiente para credenciais (Mercado Pago, etc.)

---

## 📌 Roadmap (ideias)
- Finalizar checkout (3 etapas)
- Integração real com Mercado Pago (Pix / cartão / boleto)
- Baixa de estoque automática após pagamento confirmado
- Melhorias no painel admin (relatórios, status de pedido, etc.)

---

## 📝 Licença
Este projeto é de uso livre para fins de estudo/portfólio.  
Se você for usar comercialmente, adapte conforme suas necessidades (LGPD, antifraude, segurança, etc.).

---

## 🤝 Contribuição
Pull requests são bem-vindos.  
Abra uma issue com sugestões ou bugs encontrados.

---

**KiTenis** — Laravel + Bootstrap + Blade + Filament
