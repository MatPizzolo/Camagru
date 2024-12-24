# Camagru

## Overview
Camagru is an Instagram-like website that enables users to create and share photo montages. Built as a project for 42Madrid, this application implements a robust MVC architecture with JWT-based authentication to ensure secure user interactions.

## Features
### Core Functionality
- **Photo Capture & Editing**
  - Webcam integration for real-time photo capture
  - Upload functionality for existing images
  - Real-time image editing with filters and stickers
  - Custom frame overlays and effects

### User Management
- **Authentication & Security**
  - Secure user registration and login system
  - JWT-based authentication for API requests
  - Password reset functionality with email verification
  - Profile management and settings customization

### Social Features
- **Community Interaction**
  - Photo sharing and discovery feed
  - Commenting system on shared images
  - Like/reaction functionality
  - User notifications for social interactions

## Technology Stack
### Backend
- **PHP 8.1**
  - MVC architecture pattern
  - RESTful API design
  - Custom routing system
  - Secure session management

### Frontend
- **HTML5/CSS3/JavaScript**
  - Responsive design
  - WebRTC for camera access
  - Canvas API for image manipulation
  - AJAX for asynchronous requests

### Database
- **MySQL 8.0**
  - Relational database design
  - Optimized queries
  - Data integrity constraints

### Security
- **JWT Authentication**
  - Stateless authentication
  - Token-based API security
  - XSS and CSRF protection
  - Input validation and sanitization

## Project Structure
```
.
├── app/
│   ├── client/                 # Frontend application
│   │   ├── assets/            # Static resources
│   │   ├── css/              # Stylesheets
│   │   ├── js/               # JavaScript files
│   │   └── index.html        # Entry point
│   └── server/                # Backend application
│       ├── controllers/      # Request handlers
│       ├── models/           # Data models
│       ├── middleware/       # Request middleware
│       └── utils/            # Helper functions
├── config/                    # Configuration files
│   ├── database.php         # Database configuration
│   └── nginx.conf           # Nginx configuration
├── docker/                    # Docker configuration
│   ├── php/                 # PHP configuration
│   └── mysql/               # MySQL configuration
└── docker-compose.yml        # Docker services setup
```

## Installation & Setup
### Prerequisites
- Docker and Docker Compose
- Git
- Web browser with JavaScript enabled

### Local Development Setup
1. Clone the repository:
```bash
git clone https://github.com/yourusername/camagru.git
cd camagru
```

2. Start the Docker containers:
```bash
docker-compose up -d --build
```

3. Install dependencies:
```bash
docker-compose exec php composer install
```

4. Set up the database:
```bash
docker-compose exec php php migrations/migrate.php
```

### Accessing the Application
- Frontend: http://localhost:3000
- Backend API: http://localhost:8000
- phpMyAdmin: http://localhost:8080

## Development Guidelines
### API Endpoints
All API endpoints follow RESTful conventions:
- `POST /api/auth/register` - User registration
- `POST /api/auth/login` - User login
- `GET /api/photos` - Retrieve photos
- `POST /api/photos` - Create new photo
- `PUT /api/photos/{id}` - Update photo
- `DELETE /api/photos/{id}` - Delete photo

### Authentication Flow
1. User registers/logs in and receives JWT token
2. Token is stored in browser storage
3. Subsequent requests include token in Authorization header
4. Server validates token for each protected route

### Database Schema
```sql
-- Core tables structure
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE photos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    caption TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

## Security Considerations
- All user inputs are sanitized and validated
- Passwords are hashed using bcrypt
- JWT tokens expire after 24 hours
- File uploads are validated for type and size
- CORS policies are properly configured

## Contributing
1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License
This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments
- 42Madrid for the project requirements and guidance
- The PHP community for excellent documentation and resources
- Contributors who have helped improve this project
