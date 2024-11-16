<?php

require_once 'models/Usuario.php';
use Firebase\JWT\JWT;

class UsuarioController {

    public function register($data) {
        $username = $data['username'];
        $password = $data['password'];
        $email = $data['email'];

        if (empty($username) || empty($password) || empty($email)) {
            return jsonResponse(["message" => "Todos los campos son obligatorios"], 400);
        }

        // Verificar si el nombre de usuario ya existe
        $existingUser = Usuario::getUserByUsername($username);
        if ($existingUser) {
            return jsonResponse(["message" => "El nombre de usuario ya está en uso"], 400);
        }

        // Crear nuevo usuario
        $userId = Usuario::createUser($username, $password, $email);
        return jsonResponse(["message" => "Usuario creado exitosamente", "userId" => $userId], 201);
    }

    public function login($data) {
        $username = $data['username'];
        $password = $data['password'];

        $user = Usuario::getUserByUsername($username);
        if (!$user || !password_verify($password, $user['password'])) {
            return jsonResponse(["message" => "Credenciales incorrectas"], 401);
        }

        // Generar JWT
        $jwt = Usuario::generateJWT($user['id'], $user['username']);
        return jsonResponse(["message" => "Login exitoso", "token" => $jwt], 200);
    }

    public function getUser($data) {
        $username = $data['username'];

        // Verificar el JWT
        $authHeader = isset($data['Authorization']) ? $data['Authorization'] : null;
        if (!$authHeader) {
            return jsonResponse(["message" => "Token no proporcionado"], 401);
        }

        $jwt = str_replace('Bearer ', '', $authHeader);
        try {
            $decoded = JWT::decode($jwt, $_ENV['JWT_SECRET'], array('HS256'));
            if ($decoded->username != $username) {
                return jsonResponse(["message" => "No autorizado"], 403);
            }
        } catch (Exception $e) {
            return jsonResponse(["message" => "Token inválido"], 401);
        }

        // Obtener usuario
        $user = Usuario::getUserByUsername($username);
        return jsonResponse($user, 200);
    }
}

// Función para enviar respuestas JSON
function jsonResponse($data, $statusCode = 200) {
    header("Content-Type: application/json");
    http_response_code($statusCode);
    echo json_encode($data);
}
