# 🧾 Mini ERP - Teste Montink

Sistema desenvolvido como teste técnico para a startup **Montink**. O objetivo é criar um mini ERP simples com controle de **produtos**, **estoque**, **pedidos**, **cupons** e **carrinho com regras de frete**.

## ✅ Funcionalidades

- Cadastro e edição de produtos com estoque
- Controle de variações (opcional)
- Carrinho com gerenciamento via sessão
- Cálculo de frete baseado no subtotal
- Consulta de CEP via API do [ViaCEP](https://viacep.com.br)
- Tela de finalização do pedido
- Campo visual para cupom de desconto (a lógica é implementável)

---

## 🛠️ Tecnologias

- **PHP 8.x**
- **Laravel 10**
- **MySQL**
- **Bootstrap 5**
- API ViaCEP (consulta de endereço)

---

## 🚀 Instalação 

### 1. Clone o repositório

```bash
git clone https://github.com/caua075/teste_montink.git
cd teste-montink
cd erp_montink
```

### 2. Instale as dependências

```bash
composer install
```

### 3. Copie o `.env` e configure

```bash
cp .env.example .env
```

No `.env`, configure seu banco MySQL:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erp_montink
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 4. Gere a chave da aplicação

```bash
php artisan key:generate
```

### 5. Rode as migrações

```bash
php artisan migrate
```

---

## ▶️ Executando a aplicação

```bash
php artisan serve
```

Acesse: [http://localhost:8000](http://localhost:8000)

---

## 📦 Estrutura de Tabelas

- **produtos**: nome, preço
- **estoques**: produto_id, quantidade
- **pedidos**: subtotal, frete, total, cep, logradouro, bairro, localidade, uf
- **cupons** (a lógica pode ser implementada depois): código, valor_minimo, validade

---

## 📝 Notas

- O carrinho é armazenado na sessão do Laravel
- A aplicação está pronta para receber lógica de cupons futuramente
- A estilização foi feita com Bootstrap para facilitar a responsividade
- A consulta de CEP é feita via `fetch()` + endpoint protegido com CSRF no Laravel

---

## 📸 Prints (opcional)

Inclua aqui imagens das telas do sistema se desejar (ex: cadastro de produto, carrinho, checkout)

---

## 📄 Licença

Este projeto é apenas para fins de avaliação técnica. Todos os direitos reservados à Montink.
