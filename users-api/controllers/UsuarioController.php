<?php

require_once 'models/Usuario.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UsuarioController {

    // validate jwt return true or false validateJWT
    public function validateJWT($token) {
        if (!$token) {
            return false;
        }

        $jwt = str_replace('Bearer ', '', $token);

        try {
            JWT::decode($jwt, new Key($_ENV['JWT_SECRET'], 'HS256'));
            return true;
        } catch (Exception $e) {
            return false;
        }

    }

    public function register($data) {
        $username = $data['username'];
        $password = $data['password'];
        $email = $data['email'];
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        if (empty($username) || empty($passwordHash) || empty($email)) {
            return jsonResponse(["message" => "Todos los campos son obligatorios"], 400);
        }

        // Verificar si el nombre de usuario ya existe
        $existingUser = Usuario::getUserByUsername($username);
        if ($existingUser) {
            return jsonResponse(["message" => "El nombre de usuario ya está en uso"], 400);
        }

        // Crear nuevo usuario
        $userId = Usuario::createUser($username, $passwordHash, $email);
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

    public function getUser($data, $token) {
        $userId = $data['id'];

        if (!$this->validateJWT($token)) {
            return jsonResponse(["message" => "No autorizado"], 403);
        }

        // Obtener usuario
        $user = Usuario::getUserById($userId);

        if (!$user) {
            return jsonResponse(["message" => "Usuario no encontrado"], 404);
        }

        $userResponse['id'] = $user['id'];
        $userResponse['username'] = $user['username'];
        $userResponse['email'] = $user['email'];

        return jsonResponse($userResponse, 200);
    }

    // get all users with jwt
    public function getUsers($token) {
        if (!$this->validateJWT($token)) {
            return jsonResponse(["message" => "No autorizado"], 403);
        }

        $users = Usuario::getUsers();

        // sin usuarios
        if (!$users) {
            return jsonResponse(["message" => "No hay usuarios"], 404);
        }

        return jsonResponse($users, 200);
    }

    // update user with jwt
    public function updateUser($data, $token) {
        $userId = $data['id'];
        $username = isset($data['username']) ? $data['username'] : null;
        $password = isset($data['password']) ? $data['password'] : null;
        $email = isset($data['email']) ? $data['email'] : null;

        if (!$this->validateJWT($token)) {
            return jsonResponse(["message" => "No autorizado"], 403);
        }

        // Verificar si el nombre de usuario ya existe
        $existingUser = Usuario::getUserByUsername($username);
        if ($existingUser && $existingUser['id'] != $userId) {
            return jsonResponse(["message" => "El nombre de usuario ya está en uso"], 400);
        }

        // Actualizar usuario
        $result = Usuario::updateUser($userId, $username, $password, $email);
        if ($result) {
            return jsonResponse(["message" => "Usuario actualizado exitosamente"], 200);
        } else {
            return jsonResponse(["message" => "Error al actualizar el usuario"], 400);
        }
    }

    // delete user with jwt
    public function deleteUser($data, $token) {
        $userId = $data['id'];

        if (!$this->validateJWT($token)) {
            return jsonResponse(["message" => "No autorizado"], 403);
        }

        // Eliminar usuario
        $result = Usuario::deleteUser($userId);
        if ($result) {
            return jsonResponse(["message" => "Usuario eliminado exitosamente"], 200);
        } else {
            return jsonResponse(["message" => "Error al eliminar el usuario"], 400);
        }
    }
 
}

// Función para enviar respuestas JSON
function jsonResponse($data, $statusCode = 200) {
    header("Content-Type: application/json");
    http_response_code($statusCode);
    echo json_encode($data);
}
