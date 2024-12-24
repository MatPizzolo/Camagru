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

-- Create the 'pictures' table to store user-uploaded pictures
CREATE TABLE IF NOT EXISTS pictures (
  id INT AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for each picture
  user_id INT NOT NULL, -- Foreign key referencing the user who uploaded the picture
  image_url VARCHAR(255) NOT NULL, -- Path or URL of the uploaded image
  description TEXT DEFAULT NULL, -- Optional caption or description for the picture
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp when the picture was uploaded
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE -- Ensure pictures are deleted when a user is removed
);

-- Create the 'likes' table to track likes on pictures
CREATE TABLE IF NOT EXISTS likes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL, -- User who liked the picture
  picture_id INT NOT NULL, -- Picture being liked
  liked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (picture_id) REFERENCES pictures(id) ON DELETE CASCADE
);

-- Create the 'comments' table to track comments on pictures
CREATE TABLE IF NOT EXISTS comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL, -- User who posted the comment
  picture_id INT NOT NULL, -- Picture being commented on
  comment TEXT NOT NULL, -- The comment text
  commented_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (picture_id) REFERENCES pictures(id) ON DELETE CASCADE
);

