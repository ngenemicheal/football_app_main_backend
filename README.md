# Football App (main_backend)

# Main Backend

This repository contains the main backend for the application. It serves as the core service that integrates with external APIs, manages user roles and permissions, and provides admin functionality for managing products, tickets, and blog posts.

## Features
- Admin CMS for managing products, blog posts, and tickets.
- API integration with:
  - Football data (e.g., match schedules, teams, league standings)
  - Store API for fetching product details and inventory.
- User authentication and role-based access (Admins, Vendors, Fans).
- Database for storing tickets bought by vendors (marked as printed or deleted after printing).
- API gateway for communication with the **Ticket Service** and **Payment Service**.

## Tech Stack
- **PHP** (Laravel Framework)
- **MySQL** (Database)
- **HTTP Client** for external requests
- **Blade Templates** (Admin frontend)
- **JWT Authentication**

## Installation
1. Clone the repository:
    ```bash
    git clone https://github.com/yourusername/main-backend.git
    cd main-backend
   
2. Install dependencies:
    ```bash
    composer install

3. Configure environment variables:
    ```bash
    cp .env.example .env
    php artisan key:generate
    
4. Set up the database and run migrations:
    ```bash
    php artisan migrate --seed
    
5. Start the server:
    ```bash
    php artisan serve

## Role Management
- Super Admin: Full access to the CMS.
- Blog Admin: Can post and manage blog posts.
- Vendor Admin: Can purchase tickets in bulk.
- Fans: Can buy tickets from the frontend.

## API Endpoints


>>>>>>> d735e54 (first commit)
