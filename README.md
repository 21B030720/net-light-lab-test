# NetLab E-Commerce Project

This is a full-stack e-commerce application with a Laravel backend and a Nuxt.js frontend.

## Project Structure

The project is divided into two main directories:

- `backend/`: Laravel API with Backpack Admin panel
- `frontend/`: Nuxt.js application

## Features

### Backend (Laravel)

- RESTful API endpoints for products, categories, orders, and authentication
- Laravel Backpack Admin panel for managing products, categories, and viewing orders
- Database migrations and seeders for quick setup
- Authentication using Laravel Sanctum

### Frontend (Nuxt.js)

- Product listing with search and category filtering
- Product detail pages
- Shopping cart using localStorage
- Multi-step checkout process:
  - Cart page for reviewing items
  - Checkout page for customer information and payment
  - Order confirmation page with order details
- User authentication (login/register)
- User profile page with order history

## Setup Instructions

### Backend Setup

1. Navigate to the backend directory:
   ```
   cd netlab_2/backend
   ```

2. Install PHP dependencies:
   ```
   composer install
   ```

3. Create .env file:
   ```
   cp .env.example .env
   ```

4. Generate application key:
   ```
   php artisan key:generate
   ```

5. Configure your database in the .env file. For SQLite (easy setup):
   ```
   DB_CONNECTION=sqlite
   ```
   Then create an empty SQLite database:
   ```
   touch database/database.sqlite
   ```

   Or for MySQL:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=netlab_ecommerce
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

6. Run migrations and seeders:
   ```
   php artisan migrate --seed
   ```

7. Create a symbolic link for storage:
   ```
   php artisan storage:link
   ```

8. Start the development server:
   ```
   php artisan serve
   ```
   The backend API will be available at http://localhost:8000

### Frontend Setup

1. Navigate to the frontend directory:
   ```
   cd netlab_2/frontend
   ```

2. Install dependencies:
   ```
   npm install
   ```

3. Start the development server:
   ```
   npm run dev
   ```
   The frontend will be available at http://localhost:3000

## Admin Panel

The admin panel is available at http://localhost:8000/admin

Default credentials:
- Email: test@example.com
- Password: password

## API Endpoints

### Products
- `GET /api/products` - List all products with pagination
- `GET /api/products/{id}` - Get a specific product

### Categories
- `GET /api/categories` - List all categories

### Orders
- `POST /api/orders` - Create a new order
- `GET /api/orders` - Get user's orders (requires authentication)

### Authentication
- `POST /api/register` - Register a new user
- `POST /api/login` - Login
- `POST /api/logout` - Logout (requires authentication)

## Checkout Flow
The application implements a three-step checkout process:
1. **Cart Page** (`/cart`)
   - Review cart items and quantities
   - Update quantities or remove items
   - View order summary with subtotal, tax, and total
   - Proceed to checkout
2. **Checkout Page** (`/checkout`)
   - Enter customer information (name, email, phone)
   - Provide shipping address
   - Select payment method (simulated)
   - Add order notes
   - Place order
3. **Order Confirmation Page** (`/order-confirmation`)
   - View order confirmation with order ID
   - See detailed order information
   - Print receipt option
   - Continue shopping

## Technologies Used

### Backend
- Laravel 12
- Laravel Backpack for admin panel
- Laravel Sanctum for API authentication
- SQLite/MySQL database

### Frontend
- Nuxt.js 3
- Vue.js 3
- Tailwind CSS for styling
- Composables for cart and API functionality
- LocalStorage for cart persistence

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).