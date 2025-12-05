# Laravel E-Commerce API

This is a RESTful API built with Laravel 12 for a simple e-commerce application. It handles user authentication (JWT), product management, and order processing with stock management.

## Prerequisites

- PHP >= 8.1
- Composer
- MySQL or any other supported database

## Installation

1.  **Clone the repository:**

    ```bash
    git clone <repository-url>
    cd example-app
    ```

2.  **Install dependencies:**

    ```bash
    composer install
    ```

3.  **Environment Setup:**
    Copy the `.env.example` file to `.env`:

    ```bash
    cp .env.example .env
    ```

    Update the database configuration in `.env`:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

4.  **Generate Application Key:**

    ```bash
    php artisan key:generate
    ```

5.  **Generate JWT Secret:**

    ```bash
    php artisan jwt:secret
    ```

6.  **Run Migrations:**
    ```bash
    php artisan migrate
    ```

## Running the Application

Start the local development server:

```bash
php artisan serve
```

The API will be available at `http://localhost:8000/api`.

---

## API Documentation

### Authentication

**1. Register**

- **Endpoint:** `POST /api/auth/register`
- **Body:**
    ```json
    {
        "name": "John Doe",
        "email": "john@example.com",
        "password": "password123",
        "password_confirmation": "password123"
    }
    ```

**2. Login**

- **Endpoint:** `POST /api/auth/login`
- **Body:**
    ```json
    {
        "email": "john@example.com",
        "password": "password123"
    }
    ```
- **Response:** Returns a JWT token.

**3. Get User Profile**

- **Endpoint:** `GET /api/auth/me`
- **Headers:** `Authorization: Bearer <token>`

**4. Logout**

- **Endpoint:** `POST /api/auth/logout`
- **Headers:** `Authorization: Bearer <token>`

### Products (Protected)

All product endpoints require a valid JWT token.

**1. List Products**

- **Endpoint:** `GET /api/products`

**2. Create Product**

- **Endpoint:** `POST /api/products`
- **Body:**
    ```json
    {
        "name": "Product Name",
        "description": "Product Description",
        "price": 99.99,
        "stock": 10
    }
    ```
    _Note: Status is automatically set to `in_stock` or `out_of_stock` based on the stock value._

**3. Get Product**

- **Endpoint:** `GET /api/products/{id}`

**4. Update Product**

- **Endpoint:** `PUT /api/products/{id}`
- **Body:** (Partial updates allowed)
    ```json
    {
        "price": 89.99,
        "stock": 5
    }
    ```

**5. Delete Product**

- **Endpoint:** `DELETE /api/products/{id}`

### Orders (Protected)

**1. Create Order**

- **Endpoint:** `POST /api/orders`
- **Headers:** `Authorization: Bearer <token>`
- **Body:**
    ```json
    {
        "address": "123 Main St, City",
        "phone": "+1234567890"
    }
    ```
- **Logic:**
    - Uses products currently in the user's cart.
    - Validates stock availability.
    - Decrements product stock.
    - Updates product status to `out_of_stock` if stock reaches 0.
    - Clears the user's cart.
- **Response:**
    ```json
    {
        "message": "Order created successfully",
        "order_number": "ABC123XYZ",
        "total": 199.98,
        "items": [
            {
                "product": "Product Name",
                "quantity": 2,
                "price": 99.99
            }
        ]
    }
    ```
