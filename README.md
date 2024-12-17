# Camagru

## Overview

An instagram-like website allowing its users to create and share photomontage.
It has been structured to follow best practices for a MVC web application.

A project for 42Madrid
[Subject](img/en.subject.pdf)

## Stack
Server-side - PHP, MySQL<br>
Client-side - HTML, CSS, JS<br>
structure - MVC

## Project Structure
    .
    ├── app/                 # Core application logic (controllers, models)
    ├── config/              # Configuration files (database connections, etc.)
    ├── public/              # Public-facing files (entry point, assets)
    ├── routes/              # Route definitions for the application
    ├── .htaccess            # Apache configuration for URL rewriting
    ├── docker-compose.yml   # Docker configuration
    ├── .git/
    └── README.md

## Running the Project

### 1. Using PHP's Built-in Server

1. Move to the \`public\` directory:
2. 
   ```bash
   cd public
   ```
3. Start the built-in PHP server:
   ```bash
   php -S localhost:8000
   ```
4. Open your web browser and navigate to \`http://localhost:8000\` to access the application.

### 2. Database Configuration

If your project uses a database:

1. **Create a Database**: Use phpMyAdmin to create a database for the Camagru project.
2. **Configure Database Connection**: Update the database connection settings in the configuration files located in the \`config\` directory.

## Docker-Compsoe File Explanation

#### app Service:
- Uses the official php:8.1-apache image.
- Exposes port 8000 for your PHP server.
- Mounts your local public folder to /var/www/html in the container.
- Restarts automatically if it stops.

#### db Service:
- Uses the mysql:8.0 image.
- Sets up a MySQL database with default credentials.

#### phpmyadmin Service:
- Provides an interface for managing your MySQL database.

#### volumes:
- db_data: Persists MySQL data.

