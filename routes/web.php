<?php
// /routes/web.php
require_once __DIR__ . '/../app/controllers/UserController.php';

$controller = new UserController();

if (isset($_GET['action']) && $_GET['action'] == 'register') {
    $controller->register();
} else {
        $controller->showRegisterForm();
    exit;
}

?>

