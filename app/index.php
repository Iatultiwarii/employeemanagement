<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
ob_start();
require __DIR__ . '/config.php';
require __DIR__ . '/models/User.php';

$route = isset($_GET['route']) ? $_GET['route'] : 'login';

try {
    switch ($route) {
        case 'login':
            require __DIR__ . '/controllers/AuthController.php';
            (new AuthController())->login();
            break;
        case 'signup':
            require __DIR__ . '/controllers/AuthController.php';
            (new AuthController())->signup();
            break;
        case 'profile':
            require __DIR__ . '/controllers/ProfileController.php';
            (new ProfileController())->view();
            break;
        case 'logout':
            require __DIR__ . '/controllers/AuthController.php';
            (new AuthController())->logout();
            break;
        default:
            http_response_code(404);
            if (file_exists(__DIR__ . '/app/views/errors/404.php')) {
                include __DIR__ . '/views/errors/404.php';
            } else {
                die('404 Page Not Found');
            }
    }
}
catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
    http_response_code(500);
    if (file_exists(__DIR__ . '/app/views/errors/500.php')) {
        include __DIR__ . '/app/views/errors/500.php';
    } else {
        die('500 Internal Server Error: ' . htmlspecialchars($e->getMessage()));
    }
}