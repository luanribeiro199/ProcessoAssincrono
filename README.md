Sistema de Processamento de Pedidos Assíncronos – Laravel

📌 Descrição do Projeto
Este projeto é um sistema que simula o processamento assíncrono de pedidos em um e-commerce, utilizando **Laravel**, **Jobs**, **Queues** e **API RESTful**.  
O objetivo é demonstrar como operações demoradas (como cálculo de frete e envio de notificações) podem ser realizadas em background enquanto a API permanece rápida e responsiva.

---

🚀 Funcionalidades

### ✔ Criação de Pedidos (API)
- Endpoint: **POST /api/orders**
- Salva o pedido com status inicial **pending**
- Despacha automaticamente um job para processamento assíncrono

### ✔ Processamento Assíncrono (Queues)
O Job `ProcessOrderJob` realiza:
1. Simulação de cálculo de frete → `sleep(5)`
2. Atualização do status para **processing**
3. Simulação de envio de notificação → `sleep(2)`
4. Atualização do status para **completed**

### ✔ Relacionamentos
- Order (1) → (N) OrderItems  
- Utiliza Eloquent Models e Migrations

### ✔ Testes Automatizados
Inclui no mínimo:
- Teste de criação de pedido (HTTP 201)
- Teste de verificação do disparo do Job para a fila

---

🛠 Como Rodar o Projeto

### 1️⃣ Clonar o Repositório
```
git clone <seu-repositorio>
cd <pasta-do-projeto>
```

### 2️⃣ Instalar Dependências
```
composer install
```

### 3️⃣ Configurar o .env
```
cp .env.example .env
php artisan key:generate
```

Configure o banco (recomendado sqlite para testes):

```
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Crie o arquivo:
```
touch database/database.sqlite
```

### 4️⃣ Rodar as Migrations
```
php artisan migrate
```

### 5️⃣ Iniciar o Queue Worker
Em um terminal:
```
php artisan queue:work
```

### 6️⃣ Testar a API
Exemplo de body:
```json
{
  "customer_name": "Luan Ribeiro",
  "items": [
    { "product": "Teclado", "price": 150, "quantity": 1 },
    { "product": "Mouse", "price": 80, "quantity": 2 }
  ]
}
```

Faça o POST via Insomnia/Postman:  
`http://localhost:8000/api/orders`

### 7️⃣ Rodar Testes
```
php artisan test
```

---

📂 Estrutura Esperada do Projeto
- app/Models/Order.php  
- app/Models/OrderItem.php  
- app/Http/Controllers/OrderController.php  
- app/Jobs/ProcessOrderJob.php  
- database/migrations/\*  
- tests/Feature/OrderTest.php  

---

🎯 Objetivo do Projeto
Demonstrar, em um ambiente real de API, como separar tarefas demoradas para background usando o sistema de filas do Laravel, garantindo performance e escalabilidade.

---

---

🎬 Entregas
Vídeo explicativo no youtube: https://youtu.be/H37Bs_8QzEM 
Link do github: https://github.com/luanribeiro199/ProcessoAssincrono.git 

---

👤 Autor
Luan Vinicius Ribeiro  
