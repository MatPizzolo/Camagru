<?php

// /public/index.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ .  '/../config/database.php';

if (!Database::isConnected()) {
    error_log("Error: Could not connect to the database. Please check your configuration.");
    die("A database connection error occurred.");
} else {
    error_log("Successfully connected to the database!");
}

// require_once __DIR__ . '/../routes/web.php';

require_once __DIR__ . '/../app/Router.php';
require_once __DIR__ . '/../app/Controllers/HomeController.php';

$router = new Router();

// Define routes
$router->add('/', [HomeController::class, 'index']);
$router->add('/about', function () {
    echo "About Page";
});

// Dispatch based on the current URI
$router->dispatch($_SERVER['REQUEST_URI']);




?>