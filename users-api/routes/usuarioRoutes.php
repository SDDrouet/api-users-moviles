<?php

require_once 'controllers/UsuarioController.php';

$usuarioController = new UsuarioController();

$method = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = trim($requestUri, '/');
$baseUri = 'www/users-api';
$relativeUri = str_replace($baseUri, '', $requestUri);
$relativeUri = ltrim($relativeUri, '/');

// Registrar nuevo usuario
if ($method == 'POST' && $relativeUri == 'register') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $usuarioController->register($data);
    echo "holaasd";
}

// Login de usuario
if ($method == 'POST' && $relativeUri == 'login') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $usuarioController->login($data);
}

// Obtener usuario
if ($method == 'GET' && preg_match('/^user\/(.+)$/', $relativeUri, $matches)) {
    $data = json_decode(file_get_contents('php://input'), true);
    $data['username'] = $matches[1];  // Agregar el username desde la URL
    $data['Authorization'] = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
    echo $usuarioController->getUser($data);
}
