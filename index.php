<?php
require_once __DIR__ . '/config.php'; // Asegura que la configuración se cargue correctamente

// Get the request URI and remove BASE_URL
$request = $_SERVER['REQUEST_URI'];
$request = str_replace(BASE_URL, '', $request);
$request = trim($request, '/');

// Route the request
switch ($request) {
    case '':
    case 'index':
        require VIEWS_PATH . 'index.php';
        break;

    case 'create-task':
        require CONTROLLER_PATH . '_create-task.php';
        break;

    case 'update-status':
        require CONTROLLER_PATH . '_update-status.php';
        break;

    case 'delete-task':
        require CONTROLLER_PATH . '_delete-task.php';
        break;

    default:
        require VIEWS_PATH . '404.php';
        break;
}
?>
