<?php
// /routes/web.php
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


?>

