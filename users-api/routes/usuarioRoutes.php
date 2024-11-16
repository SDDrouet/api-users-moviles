<?php

require_once 'controllers/UsuarioController.php';

$usuarioController = new UsuarioController();

$method = $_SERVER['REQUEST_METHOD'];
$relativeUri = getApiRequested();

// Obtener la API solicitada
function getApiRequested() {
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $requestUri = trim($requestUri, '/');
    $baseUri = BASE_FOLDER . "/api/";
    $position = strpos($requestUri, $baseUri);
    return substr($requestUri, $position + strlen($baseUri));
}

// Obtener token del header
function getToken() {
    $headers = getallheaders();
    return isset($headers['Authorization']) ? $headers['Authorization'] : null;
}

// Manejar rutas
switch (true) {
    // Registrar nuevo usuario
    case $method == 'POST' && $relativeUri == 'users':
        $data = json_decode(file_get_contents('php://input'), true);
        echo $usuarioController->register($data);
        break;

    // Login de usuario
    case $method == 'POST' && $relativeUri == 'login':
        $data = json_decode(file_get_contents('php://input'), true);
        echo $usuarioController->login($data);
        break;

    // Obtener usuario por username
    case $method == 'GET' && preg_match('/^users\/(.+)$/', $relativeUri, $matches):
        $data = json_decode(file_get_contents('php://input'), true);
        $data['id'] = $matches[1];
        echo $usuarioController->getUser($data, getToken());
        break;

    // Obtener todos los usuarios
    case $method == 'GET' && $relativeUri == 'users':
        echo $usuarioController->getUsers(getToken());
        break;

    // Actualizar usuario
    case $method == 'PUT' && preg_match('/^users\/(.+)$/', $relativeUri, $matches):
        $data = json_decode(file_get_contents('php://input'), true);
        $data['id'] = $matches[1];
        echo $usuarioController->updateUser($data, getToken());
        break;

    // Eliminar usuario
    case $method == 'DELETE' && preg_match('/^users\/(.+)$/', $relativeUri, $matches):
        $data = json_decode(file_get_contents('php://input'), true);
        $data['id'] = $matches[1];
        echo $usuarioController->deleteUser($data, getToken());
        break;

    // Ruta no válida
    default:
        http_response_code(400);
        echo json_encode([
            'error' => 'API no válida',
            'method' => $method,
            'uri' => $relativeUri,
        ]);
        break;
}
