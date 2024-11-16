<?php

require_once 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('BASE_FOLDER', basename(__DIR__));

// Habilitar CORS para todas las rutas
header("Access-Control-Allow-Origin: *");  // Permite todos los orígenes. Para restringir, puedes poner el dominio específico.
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");  // Permite estos métodos.
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");  // Permite estos encabezados.

// Si es una solicitud OPTIONS (preflight), solo devolver los encabezados y no hacer nada más.
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    echo json_encode(['message' => 'Preflight request']);
    http_response_code(200);
    exit();
}


require_once 'routes/usuarioRoutes.php';
