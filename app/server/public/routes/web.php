<?php
// /app/server/routes/web.php

// Set the allowed origin (use the actual frontend URL, not a wildcard)
header("Access-Control-Allow-Origin: http://localhost:3000"); // Replace with your frontend URL
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Allow these HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); // Allow these headers
header("Access-Control-Allow-Credentials: true"); // Allow credentials (cookies, etc.)

// Return JSON and not TXT type
header('Content-Type: application/json');

// Handle preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	http_response_code(200);
	exit(); // Respond with a 200 status and exit
}

require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/../../app/controllers/DefaultController.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/PictureController.php';
require_once __DIR__ . '/../../app/controllers/UserController.php';
require_once __DIR__ . '/../../app/controllers/AuthMiddlewareController.php';


$router = new Router();

// Public routes (no middleware)
$router->add('/', [DefaultController::class, 'home'], ['GET']);
$router->add('/api/', [DefaultController::class, 'apiStatus'], ['GET']);
$router->add('/api/test-jwt', [DefaultController::class, 'testJwt'], ['GET']);

$router->add('/api/register', [AuthController::class, 'register'], ['POST']); 
$router->add('/api/login', [AuthController::class, 'login'], ['POST']);      

// Protected routes (with JWT middleware)
$router->add('/api/info-me/', [UserController::class, 'getUserInfoById'], ['GET'], [AuthMiddleware::class]);
// $router->add('/api/info-me-id/{userId}', [UserController::class, 'getUserInfoById'], ['GET']);

$router->add('/api/pictures/upload', [PictureController::class, 'upload'], ['POST'], [AuthMiddleware::class]);
$router->add('/api/pictures/posts/', [PictureController::class, 'showPosts'], ['GET'], [AuthMiddleware::class]);
$router->add('/api/pictures/post/', [PictureController::class, 'showPost'], ['GET'], [AuthMiddleware::class]);
$router->add('/api/pictures/postLikes/', [PictureController::class, 'getUsersWhoLikedPost'], ['POST'], [AuthMiddleware::class]);
$router->add('/api/pictures/like', [PictureController::class, 'like'], ['POST'], [AuthMiddleware::class]);
$router->add('/api/pictures/unlike', [PictureController::class, 'unlike'], ['POST'], [AuthMiddleware::class]);
$router->add('/api/pictures/comment', [PictureController::class, 'comment'], ['POST'], [AuthMiddleware::class]);



// Dispatch based on the current URI and method
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
$router->dispatch($requestUri, $requestMethod);
?>