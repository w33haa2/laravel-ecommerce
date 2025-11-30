# Laravel E-commerce Microservices Application

A modern, scalable e-commerce platform built with Laravel microservices architecture, featuring a Vue.js frontend and containerized deployment.

## 📋 Table of Contents

- [Overview](#overview)
- [Architecture](#architecture)
- [Prerequisites](#prerequisites)
- [Project Structure](#project-structure)
- [Tech Stack](#tech-stack)
- [Getting Started](#getting-started)
- [Development Workflow](#development-workflow)
- [API Documentation](#api-documentation)
- [Deployment](#deployment)
- [Paradigms & Patterns](#paradigms--patterns)

## 🎯 Overview

This is a microservices-based e-commerce application consisting of four independent Laravel services that work together to provide a complete shopping experience. The application follows Laravel best practices and modern software architecture patterns.

### Services

1. **`app`** - Main application with Vue.js frontend (Port 8000)
   - User authentication and authorization
   - Shopping cart management
   - Frontend UI built with Vue 3, Inertia.js, and Tailwind CSS

2. **`api-catalog`** - Product catalog microservice (Port 8001)
   - Product listing and details
   - Read-only product information

3. **`api-checkout`** - Order processing microservice (Port 8002)
   - Order creation and management
   - Order calculations
   - Integration with email service

4. **`api-email`** - Email notification microservice (Port 8003)
   - Order confirmation emails
   - Email delivery service

## 🏗️ Architecture

### Microservices Architecture

The application follows a **microservices architecture** pattern where each service:
- Has its own database (database-per-service pattern)
- Runs in isolated containers
- Communicates via HTTP/REST APIs
- Can be developed, deployed, and scaled independently

### Service Communication Flow

```
┌─────────┐
│   App   │ (Frontend + API Gateway)
└────┬────┘
     │
     ├───→ api-catalog (GET /products)
     │
     ├───→ api-checkout (POST /checkout)
     │         │
     │         └───→ api-email (POST /send-order-email)
     │
     └───→ MySQL (Separate databases per service)
```

### Database Architecture

- **`catalog_db`** - Used by `api-catalog` service
- **`checkout_db`** - Used by `api-checkout` service  
- **`email_db`** - Used by `api-email` service
- **Main database** - Used by `app` service (user data, cart)

## 📦 Prerequisites

Before you begin, ensure you have the following installed:

### Required Software

1. **Docker** (v20.10+)
   ```bash
   docker --version
   docker-compose --version
   ```

2. **Git**
   ```bash
   git --version
   ```

3. **PHP** (v8.2+ for local development, optional)
   ```bash
   php --version
   ```

4. **Composer** (for local development, optional)
   ```bash
   composer --version
   ```

5. **Node.js** (v20+) and npm (for frontend development)
   ```bash
   node --version
   npm --version
   ```

### Optional (for AWS deployment)

- **AWS CLI** (v2+)
  ```bash
  aws --version
  ```

- **AWS Account** with appropriate permissions

## 📁 Project Structure

```
laravel-ecommerce/
├── app/                          # Main Laravel application
│   ├── app/
│   │   ├── Actions/             # Business logic actions
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   └── Requests/        # Form request validation
│   │   └── Models/
│   ├── resources/
│   │   ├── js/                  # Vue.js frontend
│   │   │   ├── views/          # Vue components
│   │   │   └── app.js
│   │   └── css/
│   ├── routes/
│   │   ├── api.php              # API routes
│   │   └── web.php              # Web routes
│   ├── Dockerfile
│   └── composer.json
│
├── api-catalog/                  # Catalog microservice
│   ├── app/
│   │   ├── Actions/
│   │   ├── Http/Controllers/
│   │   └── Models/
│   ├── routes/api.php
│   ├── Dockerfile
│   └── cloudformation.yml        # AWS CloudFormation template
│
├── api-checkout/                 # Checkout microservice
│   ├── app/
│   │   ├── Actions/
│   │   ├── Http/Controllers/
│   │   └── Models/
│   ├── routes/api.php
│   └── Dockerfile
│
├── api-email/                    # Email microservice
│   ├── app/
│   │   ├── Actions/
│   │   ├── Http/Controllers/
│   │   └── Models/
│   ├── routes/api.php
│   └── Dockerfile
│
├── docker-compose.yml            # Docker Compose configuration
├── create-databases.sql          # Database initialization script
├── LARAVEL_CONVENTIONS_ANALYSIS.md
├── REFACTORING_SUMMARY.md
└── README.md                     # This file
```

### Key Directories Explained

- **`app/Actions/`** - Contains business logic classes (Action pattern)
- **`app/Http/Requests/`** - Form request validation classes
- **`app/Http/Controllers/`** - Thin controllers that delegate to Actions
- **`resources/js/`** - Vue.js frontend application
- **`routes/api.php`** - API endpoint definitions

## 🛠️ Tech Stack

### Backend

- **Laravel 12** - PHP framework
- **PHP 8.4** - Programming language
- **MySQL 8.0** - Database
- **Laravel Sanctum** - API authentication
- **Laravel Fortify** - Authentication backend (app service)

### Frontend

- **Vue.js 3** - Progressive JavaScript framework
- **Inertia.js** - Server-driven single-page applications
- **Tailwind CSS 4** - Utility-first CSS framework
- **TypeScript** - Type-safe JavaScript
- **Vite 7** - Build tool and dev server
- **Pinia** - State management
- **Vue Router 4** - Client-side routing

### Infrastructure & DevOps

- **Docker** - Containerization
- **Docker Compose** - Multi-container orchestration
- **Laravel Sail** - Docker development environment
- **AWS CloudFormation** - Infrastructure as Code (optional)
- **Mailpit** - Email testing (development)

### Development Tools

- **ESLint** - JavaScript linting
- **Prettier** - Code formatting
- **Laravel Pint** - PHP code style fixer
- **PHPUnit** - PHP testing framework

## 🚀 Getting Started

### 1. Clone the Repository

```bash
git clone <repository-url>
cd laravel-ecommerce
```

### 2. Environment Setup

Each service has its own `.env` file. Copy the example files:

```bash
# Main app
cp app/.env.example app/.env

# Microservices
cp api-catalog/.env.example api-catalog/.env
cp api-checkout/.env.example api-checkout/.env
cp api-email/.env.example api-email/.env
```

### 3. Configure Environment Variables

Edit each `.env` file with appropriate values:

**`app/.env`**:
```env
APP_NAME="Laravel E-commerce"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

# Microservice URLs
API_CATALOG_URL=http://api-catalog:80
API_CHECKOUT_URL=http://api-checkout:80
API_EMAIL_URL=http://api-email:80
```

**`api-catalog/.env`**:
```env
APP_NAME="Catalog API"
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=catalog_db
DB_USERNAME=sail
DB_PASSWORD=password
```

**`api-checkout/.env`**:
```env
APP_NAME="Checkout API"
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=checkout_db
DB_USERNAME=sail
DB_PASSWORD=password
API_EMAIL_URL=http://api-email:80
```

**`api-email/.env`**:
```env
APP_NAME="Email API"
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=email_db
DB_USERNAME=sail
DB_PASSWORD=password
```

### 4. Start Docker Containers

```bash
docker-compose up -d --build
```

This will:
- Build Docker images for all services
- Start MySQL database
- Start Mailpit (email testing)
- Start all four Laravel services
- Create necessary databases

### 5. Generate Application Keys

```bash
# Main app
docker-compose exec app php artisan key:generate

# Microservices
docker-compose exec api-catalog php artisan key:generate
docker-compose exec api-checkout php artisan key:generate
docker-compose exec api-email php artisan key:generate
```

### 6. Run Migrations

```bash
# Main app
docker-compose exec app php artisan migrate

# Microservices
docker-compose exec api-catalog php artisan migrate
docker-compose exec api-checkout php artisan migrate
docker-compose exec api-email php artisan migrate
```

### 7. Install Frontend Dependencies (if needed)

```bash
docker-compose exec app npm install
docker-compose exec app npm run build
```

### 8. Access the Application

- **Main App**: http://localhost:8000
- **Catalog API**: http://localhost:8001
- **Checkout API**: http://localhost:8002
- **Email API**: http://localhost:8003
- **Mailpit Dashboard**: http://localhost:8025

## 💻 Development Workflow

### Running Services

```bash
# Start all services
docker-compose up -d

# View logs
docker-compose logs -f

# View logs for specific service
docker-compose logs -f app
docker-compose logs -f api-catalog

# Stop all services
docker-compose down

# Stop and remove volumes
docker-compose down -v
```

### Running Artisan Commands

```bash
# Main app
docker-compose exec app php artisan <command>

# Microservices
docker-compose exec api-catalog php artisan <command>
docker-compose exec api-checkout php artisan <command>
docker-compose exec api-email php artisan <command>
```

### Frontend Development

```bash
# Start Vite dev server (hot reload)
docker-compose exec app npm run dev

# Build for production
docker-compose exec app npm run build
```

### Database Access

```bash
# Connect to MySQL
docker-compose exec mysql mysql -u sail -ppassword

# Or use MySQL client
mysql -h 127.0.0.1 -P 3306 -u sail -ppassword
```

### Running Tests

```bash
# Main app
docker-compose exec app php artisan test

# Microservices
docker-compose exec api-catalog php artisan test
```

## 📡 API Documentation

### Main App API (`http://localhost:8000/api`)

#### Authentication
- `POST /api/register` - Register new user
- `POST /api/login` - User login
- `POST /api/logout` - User logout (requires auth)
- `GET /api/user` - Get current user (requires auth)

#### Cart
- `GET /api/cart` - Get user's cart (requires auth)
- `POST /api/cart` - Add item to cart (requires auth)
- `PUT /api/cart/{id}` - Update cart item (requires auth)
- `DELETE /api/cart/{id}` - Remove cart item (requires auth)

### Catalog API (`http://localhost:8001/api`)

- `GET /api/products` - List all products
- `GET /api/products/{id}` - Get product details

### Checkout API (`http://localhost:8002/api`)

- `POST /api/checkout` - Place an order
- `GET /api/orders/{id}` - Get order details

### Email API (`http://localhost:8003/api`)

- `POST /api/send-order-email` - Send order confirmation email

## 🚢 Deployment

### Local Development

The application runs entirely in Docker containers using Docker Compose. See [Getting Started](#getting-started) section.

### AWS Deployment

The project includes AWS CloudFormation templates for deploying to AWS. See `api-catalog/cloudformation.yml` for an example.

#### AWS Deployment Options

1. **EC2 with Docker Compose** (Free Tier eligible)
   - Use the CloudFormation template in `api-catalog/cloudformation.yml`
   - Deploys to a single EC2 instance (t2.micro)
   - Runs all services via Docker Compose

2. **ECS Fargate** (Recommended for production)
   - Container orchestration
   - Auto-scaling capabilities
   - Better isolation between services

3. **EC2 Instances** (Traditional)
   - Multiple EC2 instances
   - One service per instance
   - More control, higher cost

#### AWS Deployment Steps

1. **Prepare AWS Environment**
   ```bash
   aws configure
   ```

2. **Create EC2 Key Pair**
   ```bash
   aws ec2 create-key-pair --key-name laravel-ecommerce
   ```

3. **Deploy CloudFormation Stack**
   ```bash
   aws cloudformation create-stack \
     --stack-name laravel-ecommerce \
     --template-body file://api-catalog/cloudformation.yml \
     --parameters ParameterKey=KeyName,ParameterValue=laravel-ecommerce
   ```

4. **Access Your Application**
   ```bash
   # Get public IP
   aws cloudformation describe-stacks \
     --stack-name laravel-ecommerce \
     --query 'Stacks[0].Outputs'
   ```

## 🎨 Paradigms & Patterns

### Architectural Patterns

1. **Microservices Architecture**
   - Each service is independently deployable
   - Services communicate via HTTP/REST
   - Database-per-service pattern
   - Service isolation and autonomy

2. **Action Pattern**
   - Business logic extracted to Action classes
   - Controllers remain thin
   - Better testability and reusability
   - Single Responsibility Principle

3. **Form Request Validation**
   - Request validation in dedicated classes
   - Separation of concerns
   - Reusable validation rules
   - Cleaner controllers

4. **Dependency Injection**
   - Services injected via constructors
   - Loose coupling
   - Easier testing and mocking

### Design Principles

1. **SOLID Principles**
   - Single Responsibility
   - Open/Closed
   - Liskov Substitution
   - Interface Segregation
   - Dependency Inversion

2. **DRY (Don't Repeat Yourself)**
   - Shared code in Actions
   - Reusable components
   - Centralized configuration

3. **Separation of Concerns**
   - Controllers handle HTTP
   - Actions handle business logic
   - Models handle data
   - Requests handle validation

### Code Organization

```
Request → Controller → Form Request (Validation) → Action (Business Logic) → Model → Database
```

### Service Communication

- **Synchronous**: HTTP/REST API calls
- **Asynchronous**: Queue jobs (for future implementation)
- **Service Discovery**: Via Docker service names in local, DNS in production

## 🔒 Security Considerations

- **Authentication**: Laravel Sanctum for API tokens
- **Authorization**: Middleware-based access control
- **Input Validation**: Form Request classes
- **SQL Injection**: Eloquent ORM protection
- **XSS Protection**: Vue.js automatic escaping
- **CSRF Protection**: Laravel built-in CSRF tokens
- **Environment Variables**: Sensitive data in `.env` files

## 📝 Additional Documentation

- [Laravel Conventions Analysis](./LARAVEL_CONVENTIONS_ANALYSIS.md) - Code quality analysis
- [Refactoring Summary](./REFACTORING_SUMMARY.md) - Refactoring details and improvements

## 🤝 Contributing

1. Follow Laravel conventions
2. Use Action pattern for business logic
3. Use Form Requests for validation
4. Write tests for new features
5. Follow PSR-12 coding standards
6. Update documentation as needed

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🆘 Troubleshooting

### Services won't start
```bash
# Check Docker is running
docker ps

# Check logs
docker-compose logs

# Rebuild containers
docker-compose up -d --build
```

### Database connection errors
```bash
# Verify MySQL is running
docker-compose ps mysql

# Check database credentials in .env files
# Ensure database names match create-databases.sql
```

### Port conflicts
```bash
# Change ports in docker-compose.yml
# Update APP_PORT environment variable
```

### Frontend not loading
```bash
# Rebuild assets
docker-compose exec app npm run build

# Clear cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
```

## 📞 Support

For issues and questions:
1. Check the troubleshooting section
2. Review service logs
3. Verify environment configuration
4. Check Laravel documentation

---

**Built with ❤️ using Laravel, Vue.js, and Docker**

