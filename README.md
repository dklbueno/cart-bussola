# 🛒 Carrinho de Compras - API Laravel

Este projeto é uma API que simula um **carrinho de compras** com cálculo do valor final da compra com base nos **itens adicionados** e na **forma de pagamento selecionada**.

O foco está na aplicação de boas práticas de arquitetura, princípios **SOLID**, e no uso do **padrão de projeto Strategy**, permitindo fácil extensão para novas formas de pagamento.

---

## 🚀 Tecnologias

- PHP 8.1+
- Laravel 10+
- Vite (para build de frontend, se necessário)
- PHPUnit (testes)

---

## 🧠 Arquitetura

A estrutura do projeto segue princípios da **Clean Architecture**, separando responsabilidades em camadas:

```
app/
├── Domain/             # Regras de negócio puras (entidades, estratégias)
├── Application/        # Casos de uso (services, orquestração)
├── Http/Controllers/   # Interface externa (controllers REST)
```

O padrão **Strategy** é aplicado para encapsular a lógica de cálculo conforme o método de pagamento.

---

## 💳 Métodos de Pagamento Suportados

- **Pix (à vista)** → 10% de desconto
- **Cartão de Crédito à Vista (1x)** → 10% de desconto
- **Cartão de Crédito Parcelado (2 a 12x)** → Juros compostos de 1% ao mês

Fórmula de juros:
```
M = P * (1 + i)^n
```
Onde:
- M = valor final
- P = valor original
- i = 0.01 (1% ao mês)
- n = número de parcelas

---

## 📥 Requisição de Checkout

**Endpoint:**

```
POST /api/checkout
```

**Payload:**

```json
{
  "payment_type": "installment", // pix, credit_card, installment
  "installments": 6,             // obrigatório apenas se "installment"
  "card": {
    "name": "João Silva",
    "number": "4111111111111111",
    "expiry": "12/27",
    "cvv": "123"
  },
  "items": [
    { "name": "Produto A", "unit_price": 100.0, "quantity": 2 },
    { "name": "Produto B", "unit_price": 50.0, "quantity": 1 }
  ]
}
```

---

## ✅ Validações

- Campos do cartão são obrigatórios para `credit_card` e `installment`
- `installments` deve estar entre 2 e 12 se `payment_type` for `installment`
- Cada item precisa de:
  - `name`: string
  - `unit_price`: float > 0
  - `quantity`: inteiro > 0

---

## 🧪 Testes

Execute os testes com:

```bash
php artisan test
```

Os testes cobrem:

- Checkout com Pix
- Checkout com Cartão à Vista
- Checkout com Cartão Parcelado
- Erros de validação

---

## 🛠️ Instalação

1. Clone o projeto:
```bash
git clone https://github.com/seu-usuario/laravel-carrinho.git
cd laravel-carrinho
```

2. Instale as dependências:
```bash
composer install
npm install && npm run dev
```

3. Configure o `.env`:
```bash
cp .env.example .env
php artisan key:generate
```

4. Rode os testes (opcional):
```bash
php artisan test
```

---

## 📂 Estrutura recomendada

```
app/
├── Domain/
│   ├── Entities/
│   ├── Contracts/
│   └── Payment/          # Estratégias de pagamento
├── Application/
│   └── Services/         # Lógica de checkout
├── Http/
│   └── Controllers/
└── ...
```

---

## 📄 Licença

Este projeto está licenciado sob a [MIT License](LICENSE).

---

## 🤝 Contato

Caso tenha dúvidas ou sugestões, fique à vontade para entrar em contato.