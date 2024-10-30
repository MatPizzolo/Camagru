# Camagru

## Overview

This project is a PHP application named Camagru. It has been structured to follow best practices for a MVC web application.

## Project Structure
    .
    ├── app/                 # Core application logic (controllers, models)
    ├── config/              # Configuration files (database connections, etc.)
    ├── public/              # Public-facing files (entry point, assets)
    ├── routes/              # Route definitions for the application
    ├── .htaccess            # Apache configuration for URL rewriting
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

