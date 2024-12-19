<?php
// /routes/web.php
/*
require_once __DIR__ . '/../app/controllers/UserController.php';

$controller = new UserController();

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'register') {
        $controller->register();
    } elseif ($_GET['action'] == 'login') {
        $controller->showLoginForm();
    }
} else {
    $controller->showRegisterForm();
}
*/

require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/../../app/controllers/HomeController.php';
require_once __DIR__ . '/../../app/controllers/UserController.php';

$router = new Router();

// Define routes
$router->add('/', [HomeController::class, 'index']);
$router->add('/login', [UserController::class, 'showLoginForm']);
$router->add('/register', [UserController::class, 'showRegisterForm']);


// Dispatch based on the current URI
$router->dispatch($_SERVER['REQUEST_URI']);

?>

