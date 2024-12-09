# 📘 API de Agenda de Contatos

API REST para gerenciamento de contatos desenvolvida com Laravel 11 para o teste técnico.

---

## 🛠️ Tecnologias Utilizadas

-   **Laravel 11**
-   **PHP 8.2**
-   **SQLite**
-   **Docker**
-   **Laravel Sail**

---

## 🚀 Funcionalidades do Projeto

-   ✅ CRUD completo de contatos
-   ✅ Exportação de contatos selecionados para CSV
-   ✅ Envio de e-mails via fila para notificação de novos contatos
-   ✅ Validações completas dos dados
-   ✅ Testes automatizados

---

## 📝 Como Executar

### **Pré-requisitos**:

-   [Docker](https://www.docker.com/)
-   [Docker Compose](https://docs.docker.com/compose/)
-   [Git](https://git-scm.com/)

### **Passo a passo**:

1. **Clone o repositório**:

    ```bash
    git clone https://github.com/seu-usuario/seu-repositorio.git
    ```

2. **Entre na pasta do projeto**:

    ```bash
    cd seu-repositorio
    ```

3. **Copie o arquivo de ambiente**:

    ```bash
    cp .env.example .env
    ```

4. **Configure as variáveis no `.env`**:

    ```env
    NOTIFICATION_MAIL=seu-email@exemplo.com
    DB_CONNECTION=sqlite
    QUEUE_CONNECTION=database
    ```

5. **Instale as dependências via Docker**:

    ```bash
    docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php82-composer:latest composer install --ignore-platform-reqs
    ```

6. **Inicie os containers**:

    ```bash
    ./vendor/bin/sail up -d
    ```

7. **Gere a chave da aplicação**:

    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

8. **Execute as migrations**:

    ```bash
    ./vendor/bin/sail artisan migrate
    ```

9. **Inicie o worker das filas**:
    ```bash
    ./vendor/bin/sail artisan queue:work --queue=back_emails
    ```

---

## 📚 Endpoints da API

### **1. Contatos**

-   **GET** `/api/contacts`  
    Lista todos os contatos paginados.  
    **Query params**:

    -   `per_page` (default: 15)

-   **POST** `/api/contacts`  
    Cria um novo contato.  
    **Exemplo de payload**:

    ```json
    {
        "zip_code": "12345678",
        "state": "SP",
        "city": "São Paulo",
        "neighborhood": "Centro",
        "street": "Rua Exemplo",
        "number": "123",
        "name": "João Silva",
        "email": "joao@exemplo.com",
        "phone": "11987654321"
    }
    ```

-   **PUT** `/api/contacts/{id}`  
    Atualiza um contato existente.  
    **Formato do payload**: Igual ao de criação.

-   **DELETE** `/api/contacts/{id}`  
    Remove um contato.

-   **POST** `/api/contacts/export`  
    Exporta contatos selecionados para CSV.  
    **Exemplo de payload**:
    ```json
    {
        "contacts": [1, 2, 3]
    }
    ```

### **2. Filas de Email**

-   **GET** `/api/queue/emails`  
    Lista todos os e-mails na fila de envio.

---

## 🧪 Executando os Testes

### **Executar todos os testes**:

```bash
./vendor/bin/sail artisan test
```

### **Executar testes específicos**:

```bash
./vendor/bin/sail artisan test tests/Feature/Contact/CreateContactTest.php
./vendor/bin/sail artisan test tests/Feature/Contact/UpdateContactTest.php
./vendor/bin/sail artisan test tests/Feature/Contact/DeleteContactTest.php
./vendor/bin/sail artisan test tests/Feature/Contact/ExportContactsTest.php
./vendor/bin/sail artisan test tests/Feature/Contact/EmailTest.php
```

---
