-- Create the database (if it doesn't already exist)
CREATE DATABASE IF NOT EXISTS webapp;

-- Use the created database
USE webapp;

-- Create the 'users' table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

-- mysql -u root -p < dbsetup.sql