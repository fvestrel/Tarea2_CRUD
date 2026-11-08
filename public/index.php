<?php 
ini_set('display_errors', '0'); 
ini_set('log_errors', '1'); 
set_exception_handler(function (Throwable $error): void { 
    http_response_code(500); 
    error_log('CRUD clientes: fallo interno de tipo ' . get_class($error)); 
    echo 'No se pudo completar la solicitud. Revisa la configuración local y los servicios de XAMPP o MAMP.'; 
});
require_once __DIR__ . '/../src/Models/Cliente.php'; 
require_once __DIR__ . '/../src/Controllers/ClienteController.php'; 
$db = require __DIR__ . '/../config/database.php'; 
$controlador = new ClienteController($db); 
$controlador->gestionar($_SERVER['REQUEST_METHOD'], $_POST, $_GET); 
